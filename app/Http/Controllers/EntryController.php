<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntryController extends Controller
{
    public function index() : JsonResponse{ //Hauptseite padlets -> index.blade.php wird aufgerufen
        //$padlets = Padlet::all(); //hier zeigts zwar alle Padlets an, aber zB nicht User -> daher nächste Zeile
        //alle Padlets inkl. Relationen anzeigen als JSON
        $entries = Entry::with(['user', 'padlet', 'comments', 'ratings'])->get(); //rufen da Methoden vom Padlet-Model auf, damit uns da alles angezeigt wird
        //return view('padlets.index', compact('padlets'));
        return response()->json($entries, 200); //Returncode 200 = alles ok; 404 nicht gefunden, 500er Problem am Server
    }

    /**
     * FindByID
     */
    public function findById (string $id) : JsonResponse{
        $entry = Entry::where('id', $id)
            ->with(['user', 'padlet', 'comments', 'ratings'])->first();
        return $entry != null ? response()->json($entry, 200) : response()->json(null, 200); //wenn null, also kein Buch vorhanden = leeres Objekt, dann soll trotzdem Code 200 sein, weils ja kein Fehler ist -> es ist nur kein Buch vorhanden
    }

    /**
     * //Volltextsuche -> verschachtelt also sowohl Ebene Entry als auch Unterebenen User + Padlet
     */
    public function findBySearchTerm (string $searchTerm) : JsonResponse {
        $entries = Entry::with(['user', 'padlet', 'comments', 'ratings'])
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('user', function ($query) use ($searchTerm) {
                $query->where('firstName', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orWhereHas('padlet', function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            })->get();

        return response()->json($entries,200);
    }

    /**
     * SAVE-Methode -> legt einen neuen Entry an und speichert diesen
     */
    public function save(Request $request) : JsonResponse { //http-request kommt rein und kein fertiges Buch
        //$request = $this->parseRequest($request);
        //dd($request); //zum Testen
        //return response()->json(null, 200); //zum Testen
        /**
         * Transaktion anlegen
         */
        DB::beginTransaction();

        try {
            $entry = Entry::create($request->all()); //create legt neuen Entry an

            /**
             * überprüfen, ob User bereits vorhanden -> wenn id bereits existiert, dann wird firstname und lastname mit neuen Werten überschrieben
             */
            /*if(isset($request['users']) && is_array($request['users'])){ //überprüfen, ob im Request User drinenn sind + ob ein Array daher kommt
                foreach($request['users'] as $user){ //falls ja, dann drüberiterieren
                    $user = User::firstOrNew([ //gibts User schon? wenn nicht, dann wird er angelegt -> dafür gibts in Eloquent Methode firstOrNew
                        'firstName' => $user['firstName'],
                        'lastName' => $user['lastName']
                    ]);
                    $entry->users()->save($user); //User muss nun noch Padlet zugeordnet werden, nachdem er angelegt wurde
                }
            }*/

            /*if(isset($request['entries']) && is_array($request['entries'])){
                foreach($request['entries'] as $entry){
                    $entry = Entry::firstOrNew([
                        'title' => $entry['title'],
                        'description' => $entry['description']
                    ]);
                    $padlet->entries()->save($entry);
                }
            }*/


            DB::commit();
            return response()->json($entry, 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Speichern des Eintrags ist fehlgeschlagen: " . $e->getMessage(), 420);
        }

    }

    /**
     * UPDATE -> updated Padlet -> im Post PUT
     */
    public function update(Request $request, string $id) : JsonResponse{
        DB::beginTransaction();

        try {
            $entry = Entry::with(['user', 'padlet', 'comments', 'ratings'])
                ->where('id', $id)->first();

            if($entry != null) {
                $request = $this->parseRequest($request);

                $entry-> update($request->all());
                //delete old iamges
                //$padlet->images()->delete();

                /*if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $image) {
                        $image = Image::firstOrNew([
                            'title' => $image['title'],
                            'url' => $image['url']
                        ]);
                        $book->images()->save($image);
                    }
                }*/

                /**
                 * das ist Alternative zu auskommentiertem Code drüber mit images -> das könnt i mit Users auch machen
                 */
                $ids = []; //leeres Array mit IDs der Users, die man reinbekommt


                if (isset($request['users']) && is_array($request['users'])) {
                    foreach ($request['users'] as $user) {
                        array_push($ids, $user['id']);
                    }
                }
                //$entry->users()->sync($ids);
                $entry->save();
            }

            DB::commit();
            //geändertes Padlet wird zurückgegeben, damit Änderungen sichtbar sind
            $entry1 = Entry::with(['user', 'padlet', 'comments', 'ratings'])
                ->where('id', $id)->first();

            //Padlet wurde angelegt
            return response()->json($entry1, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            //Padlet konnte nicht angelegt werden
            return response()->json("Speichern des Padlets ist fehlgeschlagen:  " . $e->getMessage(), 420);
        }
    }

    /**
     * DELETE
     */
    public function delete(string $id) : JsonResponse {
        $entry = Entry::where('id', $id)->first();
        if ($entry != null) {
            $entry->delete();
            return response()->json('Eintrag (' . $id . ') wurde erfolgreich gelöscht', 200);
        }
        else
            return response()->json('Eintrag konnte nicht gelöscht werden, weil es nicht existiert', 422);
    }









    //Hilfsfunktion, um Datum von JSON in gültiges Datumsformat umwandeln zu können
    private function parseRequest(Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
