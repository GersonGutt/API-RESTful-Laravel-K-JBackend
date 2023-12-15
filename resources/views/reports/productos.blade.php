<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
  <body>
  <style>
    @page {
            size: landscape; /* Cambiar a landscape para orientación horizontal */
        }
        .texto-destacado {
            color: #421A82;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
        }
        .texto-red {
            color: red;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
        }
        .borderColor {
         border: 2px solid #421A82;
         border-radius: 10px;
       }
       .border-bottom {
        border-bottom: 2px solid #421A82 !important;
        padding-bottom: 5px;
            }


.card {
  margin-bottom: 30px;
  border: none;
  -webkit-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
  -moz-box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
  box-shadow: 0px 1px 2px 1px rgba(154, 154, 204, 0.22);
}

.card-header {
  background-color: #fff;
  border-bottom: 1px solid #e6e6f2;
}

h3 {
  font-size: 20px;
}

h5 {
  font-size: 15px;
  line-height: 26px;
  color: #3d405c;
  margin: 0px 0px 15px 0px;
  font-family: 'Circular Std Medium';
}

.text-dark {
  color: #3d405c !important;
}
.bordes-table {
  border-collapse: collapse;
  width: 100%; /* Ajusta el ancho según tus necesidades */
}

.bordes-table, .bordes-table th, .bordes-table td {
  border: 1px solid #421A82; /* Define el estilo de borde que desees */
  padding: 8px; /* Agrega espacio interno para el contenido */
}
.borderless{
    border: none !important;
  }
  .border-bottom {
  border-bottom: 1px solid #421A82;
  border-top: 0px !important;
  border-left: 0px !important;
  border-right: 0px !important;
}
.border-top {
  border-bottom: 0px !important;
  border-top: 1px solid #421A82;
  border-left: 0px !important;
  border-right: 0px !important;
}
  </style>

<table class="table">
    <thead>
      <tr>
        <th class="col-2"></th>
        <th class="col-6"></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-auto align-middle"><img src="{{ public_path() . '/images/logo.jpeg' }}" alt="Logo de la Empresa" class="img-fluid rounded-circle" width="150" height="150"></td>
        <td class="w-auto">
          <h1 class="text-center texto-destacado">K & J</h1>
          <h2 class="text-center mr-5 texto-destacado">Artículos Online</h2>
          <ul style="list-style-type: none;" class="text-center">
              <li><p><sub class="texto-destacado mb-3 text-center">- Venta al por menor de otros productos N.C.P.</sub></p></li>
              <li><p><sub class="texto-destacado text-center">- Venta al por menor de aparatos electrodomésticos,</sub></p></li>
              <li><p><sub class="texto-destacado text-center">repuestos y accesorios</sub></p></li>

          </ul>
        </td>
        <td><div class="container d-flex justify-content-center align-items-center borderColor text-center">
        <div class="texto-destacado">Reporte de Productos</div>
        <small class="texto-destacado">Fecha del reporte: </small>
        <h2 class="texto-red">{{ $fechaReporte }}</h2>
      </td>
      </tr>
    </tbody>
  </table>
  <table class="table table-striped bordes-table">

    <tr>
    <th class="center texto-destacado col-4">NOMBRE</th>
    <th class="texto-destacado col-2">CANTIDAD</th>
    <th class="texto-destacado">PRECIO UNITARIO </th>
    <th class="right texto-destacado">PRECIO TOTAL</th>
    <th class="center texto-destacado">DESCRIPCION</th>
    <th class="center texto-destacado">CATEGORIA</th>
    </tr>
    <tbody>
      @foreach ($data as $item)
      <tr>
        <td class="center texto-destacado">{{ $item['nombre'] }}</td>
        <td class="left strong texto-destacado">{{ $item['cantidad'] }}</td>
        <td class="left texto-destacado">{{  $item['precioUnitario'] }}</td>
        <td class="right texto-destacado"> {{ $item['precioTotal'] }} </td>
        <td class="center texto-destacado"> {{ $item['descripcion'] }} </td>
        <td class="center texto-destacado"> {{ $item['categoria']['nombre'] }} </td>
        </tr>
      @endforeach
      @if ($data == []){
        <td class="center texto-destacado"></td>
        <td class="left strong texto-destacado"></td>
        <td class="left texto-destacado"></td>
        <td class="right texto-destacado"></td>
        <td class="center texto-destacado"></td>
      }
      @endif
    </tbody>
</table>
<div class="card-footer bg-white mt-0">
   <p class="texto-destacado"><strong>Total inversion en productos: ${{ $total }}</strong></p>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
