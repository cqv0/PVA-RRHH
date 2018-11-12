
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de afiliacion y reingreso del trabajador</title>
    <style>
        html,
        body {
            font-size: 12pt;
            font-family: Arial;
        }
        .center{
            text-align: center;
        }
        .col {
            float: left;
        }
        .dotted {
            border-bottom: 1px dotted;
        }
        .b-top {
            border-top: 1px solid;   
        }
        .no-border {
            border: 0;
        }
        .clear {
            clear: both;
        }
        .title {
            font-weight: bold;
            font-size: 20pt;
            text-align: center;
        }
        .firma {
            width: 400px;
            height: 200px;
            display: table-cell;
            vertical-align: bottom;
        }
        .primary {
            border: 1px solid;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="primary">
        <div class="head">
            <div class="col" style="width: 35%">
                <img src="{{ public_path("/img/logo.png") }}" width="200px">
            </div>
            <div class="col center" style="width: 30%">
                <br>
                <span class="title">FORMULARIO DE SOLICITUD</span>
            </div>
            <div class="col" style="width: 35%;text-align: right;">
                {{ $departure->departure_reason->departure_type->name }}
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <br>
            <div class="col" style="width: 12%">NOMBRE: </div>
            <div class="col dotted center" style="width: 88%"> {{ implode(' ', [$departure->contract->employee->first_name, $departure->contract->employee->last_name, $departure->contract->employee->mothers_last_name]) }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">OFICINA: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->contract->position->position_group->name }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">DESTINO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->destiny }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">MOTIVO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->departure_reason->name }} </div>
            <div class="clear"></div>
            <br>
            <div style="border-top: 1px solid;border-bottom: 1px solid;">
                {{-- <legend>HORARIO</legend> --}}
                <br>
                <div class="col" style="width: 25%">FECHA DE SALIDA: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->departure_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE SALIDA: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->departure_time)->format('H:i') }} </div>
                <div class="clear"></div>
                
                <div class="col" style="width: 25%">FECHA DE RETORNO: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->return_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE RETORNO: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->return_time)->format('H:i') }} </div>
                <div class="clear"></div>
                <br>
            </div>
            <br>
            <div class="col" style="width: 22%">OBSERVACIONES: </div><div class="col dotted" style="width: 78%"> {{ $departure->description }} </div>
            <div class="clear"></div>
            <div class="col">
                <p class="firma center">
                    SOLICITANTE
                </p>
            </div>
            <div class="col">
                <p class="firma center">
                    AUTORIZADO
                </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <br>
    <hr>
    <br>
    <div class="primary">
        <div class="head">
            <div class="col" style="width: 35%">
                <img src="{{ public_path("/img/logo.png") }}" width="200px">
            </div>
            <div class="col center" style="width: 30%">
                <br>
                <span class="title">FORMULARIO DE SOLICITUD</span>
            </div>
            <div class="col" style="width: 35%;text-align: right;">
                {{ $departure->departure_reason->departure_type->name }}
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <br>
            <div class="col" style="width: 12%">NOMBRE: </div>
            <div class="col dotted center" style="width: 88%"> {{ implode(' ', [$departure->contract->employee->first_name, $departure->contract->employee->last_name, $departure->contract->employee->mothers_last_name]) }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">OFICINA: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->contract->position->position_group->name }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">DESTINO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->destiny }} </div>
            <div class="clear"></div>
            <div class="col" style="width: 12%">MOTIVO: </div>
            <div class="col dotted center" style="width: 88%"> {{ $departure->departure_reason->name }} </div>
            <div class="clear"></div>
            <br>
            <div style="border-top: 1px solid;border-bottom: 1px solid;">
                <br>
                <div class="col" style="width: 25%">FECHA DE SALIDA: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->departure_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE SALIDA: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->departure_time)->format('H:i') }} </div>
                <div class="clear"></div>
                
                <div class="col" style="width: 25%">FECHA DE RETORNO: </div><div class="col dotted center" style="width: 25%"> {{ Carbon::parse($departure->return_date)->format('d/m/Y') }} </div>
                <div class="col">&nbsp;</div>
                <div class="col" style="width: 25%">HORA DE RETORNO: </div><div class="col dotted center" style="width: 20%"> {{ Carbon::parse($departure->return_time)->format('H:i') }} </div>
                <div class="clear"></div>
                <br>
            </div>
            <br>
            <div class="col" style="width: 22%">OBSERVACIONES: </div><div class="col dotted" style="width: 78%"> {{ $departure->description }} </div>
            <div class="clear"></div>
            <div class="col">
                <p class="firma center">
                    SOLICITANTE
                </p>
            </div>
            <div class="col">
                <p class="firma center">
                    AUTORIZADO
                </p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
