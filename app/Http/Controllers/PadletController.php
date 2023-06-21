<?php

namespace App\Http\Controllers;
use App\Models\Entry;
use App\Models\Padlet;
use App\Models\PadletUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadletController extends Controller
{   //JsonResponse ist Returnwert, der zurückgegeben wird
    public function index() : JsonResponse{ //Hauptseite padlets -> index.blade.php wird aufgerufen
        //alle Padlets inkl. Relationen anzeigen als JSON
        $padlets = Padlet::with(['users', 'user', 'entries'])->get(); //Methoden vom Padlet-Model aufrufen
        return response()->json($padlets, 200); //Returncode 200 = alles ok; 404 nicht gefunden, 500er Problem am Server
    }

    public function findById (string $id) : JsonResponse{
        $padlet = Padlet::where('id', $id)
            ->with(['users', 'user', 'entries'])->first();
        return $padlet != null ? response()->json($padlet, 200) : response()->json(null, 200); //wenn null, also kein Buch vorhanden = leeres Objekt, dann soll trotzdem Code 200 sein, weils ja kein Fehler ist -> es ist nur kein Buch vorhanden
    }

    /**
     * //Volltextsuche -> verschachtelt also sowohl Ebene Padlet als auch z.B. Unterebene Username
     */
    public function findBySearchTerm (string $searchTerm) : JsonResponse {
        $padlets = Padlet::with(['users', 'user', 'entries'])
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('user', function ($query) use ($searchTerm) {
                $query->where('firstName', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $searchTerm . '%');
            })->get();

        return response()->json($padlets,200);
    }

    /**
     * SAVE-Methode -> legt ein neues Padlet an und speichert dieses
     */
    public function save(Request $request) : JsonResponse { //http-request kommt rein und kein fertiges Padlet
        $request = $this->parseRequest($request);
        /**
         * Transaktion anlegen
         */
        DB::beginTransaction();

        try {
            $padlet = Padlet::create($request->all()); //neues Padlet anlegen

            /**
             * überprüfen, ob User bereits vorhanden -> wenn id bereits existiert, dann wird firstname und lastname mit neuen Werten überschrieben
             */
            if(isset($request['users']) && is_array($request['users'])){ //überprüfen, ob im Request User drinenn sind + ob ein Array daher kommt
                foreach($request['users'] as $user){ //falls ja, dann drüberiterieren
                    PadletUser::create(['padlet_id' => $padlet['id'], 'user_id' => $user['id'], 'role_id' => $user['role_id']]); //in Zwischentabelle speichern
                }
            }

            if(isset($request['entries']) && is_array($request['entries'])){
                foreach($request['entries'] as $entry){
                    $entry = Entry::firstOrNew([
                        'title' => $entry['title'],
                        'description' => $entry['description'],
                        'padlet_id' => $padlet->id,
                        'user_id' => $entry['user_id']
                    ]);
                    $padlet->entries()->save($entry);
                }
            }


            DB::commit();
            $padlet = Padlet::with(['entries', 'user', 'users'])->where('id', $padlet['id'])->first();
            return response()->json($padlet, 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Speichern des Padlets ist fehlgeschlagen: " . $e->getMessage(), 420);
        }

    }

    /**
     * UPDATE -> updated Padlet -> im Post PUT
     */
    public function update(Request $request, string $id) : JsonResponse{
        DB::beginTransaction();

        try {
            $padlet = Padlet::with(['users', 'user', 'entries'])
                ->where('id', $id)->first();

            if($padlet != null) {
                $request = $this->parseRequest($request);

                $padlet-> update($request->all());

                /**
                 * das ist Alternative zu auskommentiertem Code drüber mit images -> das könnt i mit Users auch machen
                 */
                $padlet->entries()->delete();
                if(isset($request['entries']) && is_array($request['entries'])){
                    foreach($request['entries'] as $entry){
                        $entry = Entry::firstOrNew([
                            'title' => $entry['title'],
                            'description' => $entry['description'],
                            'padlet_id' => $padlet->id,
                            'user_id' => $entry['user_id']
                        ]);
                        $padlet->entries()->save($entry);
                    }
                }

                if(isset($request['users']) && is_array($request['users'])){ //überprüfen, ob im Request User drinenn sind + ob ein Array daher kommt
                    foreach($request['users'] as $user){ //falls ja, dann drüberiterieren, wenn nicht, dann wird er angelegt -> dafür gibts in Eloquent Methode firstOrNew
                        PadletUser::firstOrNew(['padlet_id' => $padlet['id'], 'user_id' => $user['id'], 'role_id' => $user['role_id']]); //in Zwischentabelle speichern
                    }
                }
            }

            DB::commit();
            //geändertes Padlet wird zurückgegeben, damit Änderungen sichtbar sind
            $padlet1 = Padlet::with(['users', 'user', 'entries'])
                ->where('id', $id)->first();

            //Padlet wurde angelegt
            return response()->json($padlet1, 201);

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
        $padlet = Padlet::where('id', $id)->first();
        if ($padlet != null) {
            $padlet->delete();
            return response()->json('Padlet (' . $id . ') wurde erfolgreich gelöscht', 200);
        }
        else
            return response()->json('Padlet konnte nicht gelöscht werden, weil es nicht existiert', 422);
    }


    //Hilfsfunktion, um Datum von JSON in gültiges Datumsformat umwandeln zu können
    private function parseRequest(Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
