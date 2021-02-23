<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Mail\SendContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

/**
* @OA\Info(title="API Contact", version="1.0")
*
* @OA\Server(url="http://localhost:8000")
*/



class ContactController extends Controller
{

/**
 * * @OA\Post(
 * * path="/api/contact/store",
 * * summary="Guardar datos de contacto",
 * * @OA\RequestBody(
 * *    @OA\MediaType(
 * *        mediaType="application/json",
 * *         @OA\Schema(
 * *               @OA\Property(
 * *                property="name",
 * *                type="string"
 * *                ),
 * *           @OA\Property(
 * *                property="email",
 * *                type="string"
 * *              ),
 * *            @OA\Property(
 * *                property="phone",
 * *                type="string"
 * *             ),
 * *            @OA\Property(
 * *                property="message",
 * *                type="string"
 * *             ),
 * *            example={"name": "Francisco Maldonado", "email": "fran.maldo.91@gmail.com","phone": "+54 9 351 370000", "message":"Test de envio de email"}
 * *                )
 * *            )
 * *        ),
 * *        @OA\Response(
 * *                response=200,
 * *                description="OK"
 * *            )
 * * )
 * */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['contacts'] = Contact::paginate(); // 5= registros por pagina
        //Contact: es el modelo de la tabla creada con model
        // la variable contacts se ve reflejada en las vistas
        return view('Contact.index' , $data);

    }

    public function list() // apis
    {
        $data = Contact::all(); // 5= registros por pagina
        return response()->json($data, 200);
    }


    public function save(Request $request)
    {
        //return response($request);
        $contact = $request->except('_token');
        $contact = new Contact();
       $contact->name = $request->name;
       $contact->email = $request->email;
       $contact->phone = $request->phone;
       $contact->message = $request->message;
       $contact->send_mail = "1";
       $contact->save();
       return  Redirect()->route('contacts.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




     public function store(Request $request)
    {
       // dd($request); // reemplaza var_dump y die. propio de laravel

       try {
                $contact = new Contact();
                $contact->name = $request->name;
                $contact->email = $request->email;
                $contact->phone = $request->phone;
                $contact->message = $request->message;
        try {
            Mail::to($request->email)->send(new SendContact($contact));
            $contact->send_mail = "se envio el email";
        } catch (\Exception $e) {
            $contact->send_mail = "fallo de envio: {$e->getMessage()}";
        }
       $contact->save();
       } catch (\Exception $e) {
        return response()->json("se genero un error {$e->getMessage()}",404);
       }

        return response()->json("mensaje enviado con exito {{$request->email}}",201);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Contact::findOrFail($id);
        return view("Contact.show") ->with(["contactos" => $data]);;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::destroy($id);
        return  Redirect()->route('contacts.index');
    }
}
