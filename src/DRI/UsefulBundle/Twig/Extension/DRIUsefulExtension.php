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
use Twig\TwigFunction;

use DateTime;
use DateInterval;
use Exception;

use DRI\UsefulBundle\Entity\Course;

class DRIUsefulExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('dias_faltantes', [$this, 'diasFaltantes']),
            new TwigFunction('month_name', [$this, 'monthName']),
            new TwigFunction('fn_course_type', [$this, 'fnCourseType']),
        );
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            // ...
            new TwigFilter('cuenta_atras', [$this, 'cuentaAtras']),
            new TwigFilter('cuenta_anos', [$this, 'cuentaAnos']),
        ];
    }

    /**
     * @param $fecha
     * @return DateInterval|false
     * @throws Exception
     */
    public function diasFaltantes($fecha){
        $ahora = new DateTime('now');

        $faltan = date_diff($fecha, $ahora);
        
        return $faltan;
    }

    /**
     * @param DateTime $pfecha
     * @param $id
     * @return string
     */
    public function cuentaAtras(DateTime $pfecha, $id)
    {
        $fecha = $pfecha->format('Y,').($pfecha->format('m')-1).$pfecha->format(',d,H,i,s');

        $html = <<<EOJ
            <script type="text/javascript">                
                function muestraCuentaAtras(){
                    var anos, dias, horas, minutos, segundos;
                    var ahora = new Date();
                    var fechaExpiracion = new Date($fecha;)
                    var falta = Math.floor( (ahora.getTime() - fechaExpiracion.getTime()) / 1000 );
                    var cuentaAtras, diasFaltantes;
                    
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

    /**
     * @param DateTime $pfecha
     * @param $id
     * @return string
     */
    public function cuentaAnos(DateTime $pfecha, $id)
    {

        $fecha = $pfecha->format('Y,').($pfecha->format('m')-1).$pfecha->format(',d,H,i,s');

        $html = <<<EOJ
            <script type="text/javascript">
                function muestraCuentaAnos(){
                    var anos;
                    var fechaActual = new Date();
                    var fechaInicio = new Date($fecha;)
                    var transcurrido = Math.floor( (fechaActual.getTime() - fechaInicio.getTime()) / 1000 );
                    var cantAnos;
                    
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

    /**
     * @param $month
     * @return string
     */
    public function monthName($month){
        $monthView = '';
        switch ($month){
            case '01': $monthView = 'Enero'; break;
            case '02': $monthView = 'Febrero'; break;
            case '03': $monthView = 'Marzo'; break;
            case '04': $monthView = 'Abril'; break;
            case '05': $monthView = 'Mayo'; break;
            case '06': $monthView = 'Junio'; break;
            case '07': $monthView = 'Julio'; break;
            case '08': $monthView = 'Agosto'; break;
            case '09': $monthView = 'Septiembre'; break;
            case '10': $monthView = 'Octubre'; break;
            case '11': $monthView = 'Noviembre'; break;
            case '12': $monthView = 'Diciembre'; break;
            default : break;
        }
        return $monthView;
    }

    public function fnCourseType($type){
        return Course::type_AcronimToName($type);
    }

    public function getName()
    {
        return 'dri';
    }
}