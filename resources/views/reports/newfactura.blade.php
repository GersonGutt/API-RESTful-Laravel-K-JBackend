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

      </td>
      <td><div class="container d-flex justify-content-center align-items-center borderColor text-center">
      <div class="texto-destacado">F A C T U R A</div>

      <h2 class="texto-red"> N° 000{{ $factura['0']['numerofactura'] }}</h2>

    </td>
</div>
    </tr>
  </tbody>
</table>


<div class="text-end texto-destacado">Fecha:<small class="border-bottom"> {{ $factura['0']['fechaVenta'] }}</small></div>
<table class="table">
  <thead>
    <tr>
      <th class="col-1"></th>
      <th class="col-9"></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <div class="texto-destacado">Cliente:</div>
      </td>
      <td><div class="border-bottom texto-destacado">{{ $factura['0']['cliente'] }}</div></td>
        </tr>
        <br>
        <tr>
        <td>
        <div class="texto-destacado">Dirección:</div>
      </td>
      <td><div class="border-bottom texto-destacado">{{ $factura['0']['direccion'] }}</div></td>
        </tr>
        </tbody>
        </table>
        <div class="text-start texto-destacado">NIT ó DUI:<small class="border-bottom"> {{ $factura['0']['dui'] }}</small> <template style="margin-left: 350px">Vta. a Cta. de: </template> <small class="border-bottom">Efectivo</small></div>
        <br>
        <!--TODO:: TABLA DE MIERDA -->
        <div class="offset-xl-2 col-xl-8 col-md-12 col-md-12 col-sm-12 col-12 padding">
<div class="card">
        <div>
        <div class="table-responsive-md">
<table class="table table-striped bordes-table">

<tr>
<th class="center texto-destacado">CANT.</th>
<th class="texto-destacado col-5">DESCRIPCIÓN</th>
<th class="texto-destacado">PRECIO UNITARIO </th>

<th class="right texto-destacado">TOTAL</th>
</tr>
<tbody>
  @foreach ($factura['detalleVenta'] as $item)
  <tr>
    <td class="center texto-destacado">{{ $item['producto']['cantidad'] }}</td>
    <td class="left strong texto-destacado">{{ $item['producto']['nombre'] }}</td>
    <td class="left texto-destacado">{{ $item['producto']['precioUnitario'] }}</td>
         <td class="right texto-destacado"> {{ $item['producto']['cantidad'] * $item['producto']['precioUnitario'] }} </td>
    </tr>
  @endforeach
</tbody>



<
<tr>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="right texto-destacado">Venta Total</td>
    <td class="center texto-destacado text-center">{{ $factura['0']['total'] }}</td>
</tr>
</table>


</div>
</div>
        <!-- END TABLA DE MIERDA -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    </body>
</html>






