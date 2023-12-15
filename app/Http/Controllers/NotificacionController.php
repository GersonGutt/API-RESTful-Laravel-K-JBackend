<?php

namespace App\Http\Controllers;
use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $notificaciones = Notificacion:: orderBy('created_at', 'desc')->get();
            $response = $notificaciones->toArray();
            $i = 0;
            foreach ($notificaciones as $notificacion) {
                $response[$i]["user"] = $notificacion->user->toArray();

                $i++;
            }
            return $notificaciones;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }


    public function indexs()
{
    try {
        $unreadCount = Notificacion::where('is_readed', 0)->count();
        return $unreadCount;
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $notificacion = Notificacion::findOrFail($request->id);
            $notificacion->is_readed = $request->is_readed;
            if ( $notificacion->update()>=1) {
                return response()->json(['status'=>'ok', 'data'=> $notificacion], 202);

            } else {
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }
         }
        catch (\Exception $e) {}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
