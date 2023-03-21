<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use App\Models\Pedido;
use App\Models\DatosPersona;
use App\Models\TipoProducto;
use App\Models\EstadoPedido;
use App\Models\ProductosPedido;
use App\Mail\MailSender;
use App\Models\Productos;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idPedido = 0,$estadoP = 0)
    {
        if (Gate::allows('admin')) {
            $busqueda = "";
            if (isset($_REQUEST['idPedido'])) {
                $busqueda = $_REQUEST['idPedido'];
            }
            # Exista o no exista búsqueda, los ordenamos
            // $builder = Pedido::orderBy('idEstado');
            $builder = Pedido::orderBy('id', 'DESC')->whereDate('fecha', '>', Carbon::now());
            if ($busqueda) {
                $builder->where("id", "LIKE", "%$busqueda%"); 
            }

            $estado = "";
            if (isset($_REQUEST['estadoP'])) {
            
                if($_REQUEST['estadoP'] != 0)
                    // $options = implode(',', $_POST['estadoP']);
                    $estado = $_REQUEST['estadoP'];
            }
            if ($estado) {
                # Si hay búsqueda, agregamos el filtro
                $builder->where("idEstado", $estado);   
            }
            else {
                $builder->whereNotIn('idEstado', [3,5]);
            }
            
            # Al final de todo, invocamos a paginate que tendrá todos los filtros
            //$pedidos = $builder->whereNotIn('idEstado', [3,5]);
            $pedidos = $builder->paginate(5);
            // $pedidos = $builder->simplePaginate(5);

            $tipos = TipoProducto::all();
            $estados = EstadoPedido::all();

            return view('pages.pedidos.index', [
                'pedidos' => $pedidos,
                'tipos' => $tipos,
                'estados' => $estados
            ]);
                
            # Al final de todo, invocamos a paginate que tendrá todos los filtros
            //$pedidos = $builder->whereNotIn('idEstado', [3,5]);
            $tipos = TipoProducto::all();
            $estados = EstadoPedido::all();

            $pedidos = $builder->paginate(5)->setPath(route('pedidos.index'))
            ->appends([
                'idPedido' => $_REQUEST['idPedido'],
                'estadoP' => $_REQUEST['estadoP']
            ]
        );
            // $pedidos = $builder->simplePaginate(5);

        
            return view('pages.pedidos.index', [
                'pedidos' => $pedidos,
                'tipos' => $tipos,
                'estados' => $estados
            ]);
        }
        else {
            return redirect()->route('home');
        }
        
        # Al final de todo, invocamos a paginate que tendrá todos los filtros
        //$pedidos = $builder->whereNotIn('idEstado', [3,5]);
        $tipos = TipoProducto::all();
        $estados = EstadoPedido::all();
        
        $pedidos = $builder->paginate(5);
        // $pedidos = $builder->simplePaginate(5);

       
        return view('pages.pedidos.index', [
            'pedidos' => $pedidos,
            'tipos' => $tipos,
            'estados' => $estados
        ]);
    }
    public function filter()
    {
        // $pedidos = Pedido::whereNotIn('idEstado', [3,5]);
        // $pedidos = $pedidos->paginate(6);
        // $tipos = TipoProducto::all();
        // return view('pages.pedidos.index', [
        //     'pedidos' => $pedidos,
        //     'tipos' => $tipos,
        // ]);

        $busqueda = "";
        if (isset($_REQUEST['idPedido'])) {
            $busqueda = $_REQUEST['idPedido'];
        }
        # Exista o no exista búsqueda, los ordenamos
        // $builder = Pedido::orderBy('idEstado');
        $builder = Pedido::orderBy('id');
        if ($busqueda) {
            $builder->where("id", "LIKE", "%$busqueda%"); 
        }

        $estado = "";
        if (isset($_REQUEST['estadoP'])) {
           
            if($_REQUEST['estadoP'] != 0)
                // $options = implode(',', $_POST['estadoP']);
                $estado = $_REQUEST['estadoP'];
        }
        if ($estado) {
            # Si hay búsqueda, agregamos el filtro
            $builder->where("idEstado", $estado);   
        }
        else {
            $builder->whereNotIn('idEstado', [3,5]);
        }
        
        # Al final de todo, invocamos a paginate que tendrá todos los filtros
        //$pedidos = $builder->whereNotIn('idEstado', [3,5]);
        $tipos = TipoProducto::all();
        $estados = EstadoPedido::all();
        
        $pedidos = $builder->paginate(5);
        // $pedidos = $builder->simplePaginate(5);

    
        return view('pages.pedidos.index', [
            'pedidos' => $pedidos,
            'tipos' => $tipos,
            'estados' => $estados
        ]);
    }
    public function selectDisponibles(Request $request){

        //select all pedidos where fecha is $request->fecha and estado is not 4
        $pedidos = Pedido::where('fecha', $request->fecha)->whereNotIn('idEstado', [4])->get();
        //if pedidos is more than 40 return true
        if($pedidos->count() <= 40){
            
            return ['success' => true, 'data' => $pedidos->count(), 'message' => 'Hay cupo para pedidos'];
            
        }
            return ['success' => false, 'data' => $pedidos->count(), 'message' => 'No hay cupo para pedidos'];

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

        //select all pedidos where fecha is $request->fecha and estado is not 4
        $pedidos = Pedido::where('fecha', $request->fecha)->whereNotIn('idEstado', [4])->get();
        //if pedidos is more than 40 return true
        if($pedidos->count() <= 40){
            //insert pedido in database with estado 1 and fecha $request->fecha and idPersona $request->idPersona
            $pedido = new Pedido();
            // dd($request);
            $pedido->fecha = $request->fecha;
            $pedido->idPersona = $request->idPersona;
            $pedido->idEstado = 1;
            if($request->observaciones != null){
                $pedido->observacion = $request->observaciones;
            }else{
                $pedido->observacion = "Sin observaciones por el cliente";
            }
            $pedido->save();
            
            //get idPedido and insert in producto pedidos taking produtos from sesion carrito
            //$idPedido = Pedido::latest('id')->first()->id;
            $idPedido = $pedido->id;

            $carrito = session('carrito');
                
            foreach($carrito as $producto){
                $productoPedido = new ProductosPedido();
                $productoPedido->idPedido = $idPedido;
                $productoPedido->idProducto = $producto['id'];
                $productoPedido->cantidad = $producto['cantidad'];
                $productoPedido->save();
            }

            //enviar mail de confirmacion
            $personaid = $request->idPersona;
            $persona = DB::table('datos_personas')->where('id', $personaid)->first();
            $productosPedido = ProductosPedido::where('idPedido','=',$idPedido)->get();
            $mailData = ['title' => 'Pedido recibido.',
                'body' => 'Hemos recibido su encargo con número de pedido '.$idPedido,
                'productosPedido' => $productosPedido];

            Mail::to($persona->email)->send(new MailSender($mailData));
            //clear carrito and persona
            session()->forget('carrito');
            session()->forget('persona');

            return redirect()->route('home')->with('success', 'Pedido recibido, ¡muchas gracias!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        if (Gate::allows('admin')) {
            $persona =DatosPersona::where('id','=',$pedido->idPersona)->first();
            // $pedidos = $builder->simplePaginate(5);

            $tipos = TipoProducto::all();
            $estados = EstadoPedido::all();
            return view('pages.pedidos.detalle', [
                'pedido' => $pedido,
                'persona' => $persona,
                'tipos' => $tipos,
                'estados' => $estados
            ]);
        }
        else {
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }
    public function updateEstado(Pedido $pedido, $estado)
    {   

        $pedido->idEstado = $estado;
        $pedido->save();

        $platos = [];
        $nuevoEstado = EstadoPedido::find($estado);
        $persona = DatosPersona::find($pedido->idPersona);
        
        $productosPedido = ProductosPedido::where('idPedido','=',$pedido->id)->get();
        
        $resumen = [];

        $mailData = ['title'=>'El estado de su pedido ha cambiado',
            'body'=>'Su pedido número '.$pedido->id . ' a cambiado su estado a ' . $nuevoEstado->nombre,
            'productosPedido'=>$productosPedido
        ];
        Mail::to($persona->email)->send(new MailSender($mailData));
        //dd('el correo se ha mandado'. $persona->email);
    
        
        //return view('emails.emailPrueba',['mailData'=>$mailData]);
       return redirect()->route('pedidos.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
