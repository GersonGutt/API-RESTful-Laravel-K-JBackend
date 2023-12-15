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
        <ul style="list-style-type: none;">
            <li><p><sub class="texto-destacado mb-3">- Venta al por menor de otros productos N.C.P.</sub></p></li>
            <li><p><sub class="texto-destacado">- Venta al por menor de aparatos electrodomésticos,</sub></p></li>
            <li><p><sub class="texto-destacado">repuestos y accesorios</sub></p></li>

        </ul>
      </td>
      <td><div class="container d-flex justify-content-center align-items-center borderColor text-center">
      <div class="texto-destacado">F A C T U R A</div>
      <small class="texto-destacado">23HC000F</small>
      <h2 class="texto-red"> N° 000{{ $factura['0']['numerofactura'] }}</h2>
      <small class="texto-destacado">NRC: 319131-4 DUI:05676551-9</small>
      <small class="texto-destacado">NIT: 0407-070298-101-0</small>
    </td>
</div>
    </tr>
  </tbody>
</table>
<div class="texto-destacado"><p>kevin Javier Menjívar Guardado</p></div>
<div><sub class="texto-destacado">Calle Placido Peña, Bo. Las Flores,</sub></div>
<sub class="texto-destacado">Chalatenango, Chalatenango.</sub>

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
<th class="right texto-destacado">VENTAS NO SUJETAS</th>
<th class="center texto-destacado">VENTAS EXCENTAS</th>
<th class="right texto-destacado">VENTAS AFECTAS</th>
</tr>
<tbody>
  @foreach ($factura['detalleVenta'] as $item)
  <tr>
    <td class="center texto-destacado">{{ $item['producto']['cantidad'] }}</td>
    <td class="left strong texto-destacado">{{ $item['producto']['nombre'] }}</td>
    <td class="left texto-destacado">{{ $item['producto']['precioUnitario'] }}</td>
    <td class="right texto-destacado"> - </td>
    <td class="center texto-destacado"> - </td>
     <td class="right texto-destacado"> {{ $item['producto']['cantidad'] * $item['producto']['precioUnitario'] }} </td>
    </tr>
  @endforeach
</tbody>
<tr>
    <td class="borderless texto-destacado">Son:</td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="right texto-destacado">Sumas</td>
    <td class="center texto-destacado">{{ $factura['0']['total'] }}</td>
</tr>
<tr>
    <td class="border-bottom"></td>
    <td class="border-bottom"></td>
    <td class="border-bottom"></td>
    <td class="border-bottom"></td>
    <td class="right texto-destacado">(-) IVA Retenido</td>
    <td class="center texto-destacado text-center">_</td>
</tr>
<tr>
    <td class="borderless texto-destacado"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="right texto-destacado">Sub-Total</td>
    <td class="center texto-destacado text-center">_</td>
</tr>
<tr>
    <td class="borderless texto-destacado"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="right texto-destacado ">Venta no Sujeta</td>
    <td class="center texto-destacado text-center">_</td>
</tr>
<tr>
    <td class="borderless texto-destacado col-1"></td>
    <small class="texto-destacado">Recibido por</small>
    <td class="borderless"></td>
    <small style="padding-right: 80px" class="texto-destacado">Entregado por</small>
    <td class="right texto-destacado">Ventas Excentas</td>
    <td class="center texto-destacado text-center">_</td>
</tr>
<tr>
    <td class="borderless texto-destacado"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="borderless"></td>
    <td class="right texto-destacado">Venta Total</td>
    <td class="center texto-destacado text-center">{{ $factura['0']['total'] }}</td>
</tr>
</table>
<div class="card-footer bg-white mt-0">
    <p class="mb-0" style="font-size: 8px; margin-top: 0px;">
    IMPRESOS LEO, NIT:0315-141052-001, NRC: 130732-2     TIRAJE DE DOCUMENTO: 23HC000F1 al 23HC0000F300
    </p>
    <p class="mb-0" style="font-size: 8px; margin-top: 0px;">
        1° Calle Pla. Barrio El Centro Chalatenango, Telefono: 2301-0119     FECHA DE IMPRESIÓN: {{ $factura['0']['fechaVenta'] }} <strong style="margin-left: 150px">ORIGINAL - EMISOR</strong>
        </p>
        <p class="mb-0" style="font-size: 8px; margin-top: 0px;">
         AUTORIZACIÓN DE IMPRENTA: D.G.I.I: No. 848     RESOLUCION No: 15041-res-in.48291-2023 <strong style="margin-left: 168px">DUPLICADO - CLIENTE</strong>
            </p>
            <p class="mb-0" style="font-size: 8px; margin-top: 0px;">
            FECHA DE AUTORIZACIÓN D.G.I.I: 21-11-2001     FECHA DE RESOLUCION: 06-07-2023
                   </p>
    </div>
</div>
</div>

</div>
</div>
        <!-- END TABLA DE MIERDA -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    </body>
</html>






