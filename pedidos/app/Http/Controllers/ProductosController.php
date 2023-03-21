<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use App\Models\Productos;
use App\Models\TipoProducto;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Testing\MimeType;
use Intervention\Image\Facades\Image;

class ProductosController extends Controller
{
    private $disk = "public";

    /**
     * Display a listing of the resource.
     */
    public function index($id = 0)
    {
        if (Gate::allows('admin'))
            return redirect()->route('productos.catalogo');

        $builder = Productos::orderBy('nombre');

        if ($id != 0) {
            $builder->where('idTipo', '=', $id);
        }
        $busqueda = "";
        if (isset($_REQUEST['busqueda'])) {
            $busqueda = $_REQUEST['busqueda'];
        }

        if ($busqueda) {
            # Si hay búsqueda, agregamos el filtro
            $builder->where("nombre", "LIKE", "%$busqueda%");
        }
        $productos = $builder->paginate(12);

        $files = [];
        foreach (storage::disk($this->disk)->files() as $file) {
            $name = str_replace("$this->disk/", "", $file);

            $type = pathinfo(storage_path('/images/archivo1.png'));
            $picture = "";
            if (strpos($type["filename"], "archivo") !== false) {
                $picture = asset(storage::disk($this->disk)->url($name));

                // dd($picture);
                $files[substr($name, 0, (strrpos($name, ".")))] = [
                    "picture" => $picture,
                    "name" => $name,
                    "size" => storage::disk($this->disk)->size($name)
                ];
            }
        }
        $tipos = TipoProducto::all();
        return view('pages.productos.index', [
            "productos" => $productos,
            "tipos" => $tipos,
            "files" => $files,
            "id" => $id,
        ]);
    }

    public function catalogo($id = 0)
    {
        if (Gate::allows('admin')) {
            $builder = Productos::orderBy('nombre');

            if ($id != 0) {
                $builder->where('idTipo', '=', $id);
            }
            $productos = $builder->paginate(8);

            $tipos = TipoProducto::all();
            return view('pages.productos.catalogo', [
                "productos" => $productos,
                "tipos" => $tipos,
                "id" => $id,
            ]);
        } else {
            return redirect()->route('productos.index');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoProducto::all();
        return view('pages.productos.create', [
            "producto" => null,
            'tipos' => $tipos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->observacion == null){
            $request->merge([
                'observacion' => "",
            ]);
        }
        $validatedData = $request->validate([
            'nombre' => 'required|unique:productos',
            'idTipo' => 'required|in:1,2,3,4,5,6,7',
            'pedidoMinimo' => 'required|min:1',
            'precio' => 'required|numeric|gt:0',
            'observacion' => 'nullable|min:1|max:1000'
        ], [
            'nombre.required' => 'Nombre es obligatorio.',
            'nombre.unique' => 'Nombre ya existe.',
            'idTipo.in' => 'Tipo es obligatorio.',
            'pedidoMinimo.required' => 'Pedido minimo es obligatorio.',
            'precio.required' => 'Precio es obligatorio.',
            'observacion' => 'La observacion no puede ser tan larga'
        ]);

        $producto = Productos::create($validatedData);

        //Recibimos el archivo y lo guardamos en la carpeta storage/app/public
        // Si recibe un POST
        //Recibimos el archivo y lo guardamos en la carpeta storage/app/public
        if ($request->file('file')!=null) {
            //con el método getMimeType() obtengo el tipo de archivo "image/png"
            //Si el archivo que suben no es una imagen png no permite subirla.
            if ($request->file('file')->getMimeType() == "image/png") {
                $file = $request->file('file');
                //Cogemos el nombre que el usuario a introducido
                $name = $producto->id;
                //Para almacenar la imagen poniendole un nombre
                $file->storeAs('', $name . "." . $file->extension(), 'public');
                $filePath = public_path('/thumbnails');
                $img = Image::make($file->path());
                $img->resize(110, 110, function ($const) {
                    $const->aspectRatio();
                })->save($filePath . '/' . $name . "." . $file->extension());
            }
        }
        //return back()->with('success', 'producto creado correctamente.');
        return redirect()->route('productos.catalogo')->with('success', 'Producto "'.$producto->nombre.'" creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Productos $producto)
    {
        $idTipo = $producto->idTipo;
        $nombre = $producto->nombre;
        $productos = Productos::where([
            ['idTipo', '=', $idTipo],
            ['nombre', '!=', $nombre]
        ])
            ->take(4)
            ->get();

        $filename = $producto->id . ".png";
        $name = str_replace("$this->disk/", "", $filename);
        $picture = "";

        $picture = asset(storage::disk($this->disk)->url($name));


        $file[substr($name, 0, (strrpos($name, ".")))] = [
            "picture" => $picture,
            "name" => $name
        ];
        //dd($name);
        $tipo = TipoProducto::where('id', '=', $idTipo)->first();

        $tipos = TipoProducto::all();
        return view('pages.productos.show', [
            "producto" => $producto,
            "productos" => $productos,
            "tipo" => $tipo,
            "tipos" => $tipos,
            "files" => $file
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Productos $producto)
    {
        $tipos = TipoProducto::all();
        return view('pages.productos.create', [
            'producto' => $producto,
            "tipos" => $tipos
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Productos $producto)
    {
        $validatedData = $request->validate([
            'nombre' => 'required',
            'idTipo' => 'in:1,2,3,4,5,6,7',
            'pedidoMinimo' => 'required|min:1',
            'precio' => 'required|numeric|gt:0',
        ], [
            'nombre.required' => 'Nombre es obligatorio.',
            'idTipo.in' => 'Tipo es obligatorio.',
            'pedidoMinimo.required' => 'Pedido minimo es obligatorio.',
        ]);

        //Recibimos el archivo y lo guardamos en la carpeta storage/app/public
        if ($request->file('file')!=null) {
            //con el método getMimeType() obtengo el tipo de archivo "image/png"
            //Si el archivo que suben no es una imagen png no permite subirla.
            if ($request->file('file')->getMimeType() == "image/png") {
                $file = $request->file('file');
                //Cogemos el nombre que el usuario a introducido
                $name = $producto->id;
                //Para almacenar la imagen poniendole un nombre
                $file->storeAs('', $name . "." . $file->extension(), 'public');
                $filePath = public_path('/thumbnails');
                $img = Image::make($file->path());
                $img->resize(110, 110, function ($const) {
                    $const->aspectRatio();
                })->save($filePath . '/' . $name . "." . $file->extension());
            }
        }
        $producto->update($validatedData);
        // return Redirect()->back()->with('success', 'producto actualizado correctamente.');
        return redirect()->route('productos.catalogo')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Productos $producto)
    {
        $filename = $producto->id . ".png";
        // dd(public_path('images/'.$filename));
        if (\File::exists(public_path('images/' . $filename))) {
            \File::delete(public_path('images/' . $filename));
            \File::delete(public_path('thumbnails/' . $filename));
        }
        $producto->delete();
        return redirect()->route('productos.catalogo')->with('success', 'Se ha eliminado "'.$producto->nombre.'" del catálogo.');
    }
}
