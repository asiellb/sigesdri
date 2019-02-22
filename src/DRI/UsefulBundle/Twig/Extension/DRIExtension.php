<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\UsefulBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class DRIExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            'dias_faltantes' => new \Twig_Function_Method($this, 'diasFaltantes')
        );
    }

    public function getFilters()
    {
        return [
            // ...
            new TwigFilter('cuenta_atras', [$this, 'cuentaAtras']),
            new TwigFilter('cuenta_anos', [$this, 'cuentaAnos']),

            /*
            'cuenta_atras' => new \Twig_Filter_Method($this, 'cuentaAtras',
                array('is_safe' => array('html'))),
            'cuenta_anos' => new \Twig_Filter_Method($this, 'cuentaAnos',
                array('is_safe' => array('html'))),
            */
        ];
    }
    public function diasFaltantes($fecha){
        $ahora = new \DateTime('now');

        $faltan = date_diff($fecha, $ahora);
        
        return $faltan;
    }

    public function cuentaAtras($fecha, $id)
    {
        $fecha = $fecha->format('Y,').($fecha->format('m')-1).$fecha->format(',d,H,i,s');

        $html = <<<EOJ
            <script type="text/javascript">                
                function muestraCuentaAtras(){
                    var dias, horas, minutos, segundos;
                    var ahora = new Date();
                    var fechaExpiracion = new Date($fecha);
                    var falta = Math.floor( (ahora.getTime() - fechaExpiracion.getTime()) / 1000 );
                    
                    if (falta < 0) {
                        cuentaAtras = '-';
                    }
                    else {
                        anos = Math.floor(falta/31536000);
                        falta = falta % 31536000;
                        dias = Math.floor(falta/86400);
                        falta = falta % 86400;
                        horas = Math.floor(falta/3600);
                        falta = falta % 3600;
                        minutos = Math.floor(falta/60);
                        falta = falta % 60;
                        segundos = Math.floor(falta);
                        diasFaltantes = (anos < 10 ? '0' + anos : anos) + ' años';
                        cuentaAtras = (dias < 10 ? '0' + dias : dias) + 'd ' + (horas < 10 ? '0' + horas : horas) + 'h ' + (minutos < 10 ? '0' + minutos : minutos) + 'm ' + (segundos < 10 ? '0' + segundos : segundos) + 's ';
                        //setTimeout('muestraCuentaAtras()', 1000);
                    }
                    document.getElementById("$id").innerHTML = '<strong><span class="">Cierra en:</span> ' + diasFaltantes + ' <i class="fa fa-calendar-check-o"></i></strong>';
                }
                
                muestraCuentaAtras();
            </script>
EOJ;
        
        return $html;
    }

    public function cuentaAnos($fecha, $id)
    {
        $fecha = $fecha->format('Y,').($fecha->format('m')-1).$fecha->format(',d,H,i,s');

        $html = <<<EOJ
            <script type="text/javascript">
                function muestraCuentaAnos(){
                    var anos;
                    var fechaActual = new Date();
                    var fechaInicio = new Date($fecha);
                    var transcurrido = Math.floor( (fechaActual.getTime() - fechaInicio.getTime()) / 1000 );

                    if (transcurrido < 0) {
                        cantAnos = '<strong class="font-red"><i class="fa fa-warning"></i> Error en la fecha de alta!!!</strong>';
                    }
                    else {
                        anos = Math.floor(transcurrido/31536000);
                        transcurrido = transcurrido % 31536000;

                        cantAnos = anos + ' años';

                    }
                    document.getElementById("$id").innerHTML = cantAnos ;
                }

                muestraCuentaAnos();
            </script>
EOJ;

        return $html;
    }

    public function getName()
    {
        return 'dri';
    }
}