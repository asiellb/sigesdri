<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 4:49
 */

namespace DRI\UsefulBundle\Useful;


class Useful
{
    static function getSlug($preSlug, $separator = '-'){
        // Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $preSlug);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separator));
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);
        return $slug;
    }

    static function checkInRange($date_start, $date_end, $date_now) {
        $date_start = strtotime($date_start);
        $date_end = strtotime($date_end);
        $date_now = strtotime($date_now);
        if (($date_now >= $date_start) && ($date_now <= $date_end))
            return true;
        return false;
    }

    static function getCurrentPeriod() {
        $currentMonth = date('m');
        $currentYear = date('Y');
        $nextYear = date('Y')+1;
        $prevYear = date('Y')-1;

        $currentPeriod = '';

        if ($currentMonth < 8){
            $currentPeriod = sprintf('%s - %s', $prevYear, $currentYear);
        }else{
            $currentPeriod = sprintf('%s - %s', $currentYear, $nextYear);
        }

        return $currentPeriod;
    }

    static function getAge($birthday) {
        list($Y,$m,$d) = explode("-",$birthday);
        $age = ( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );

        return $age;
    }

    static function convertMonthNumberToName($month){
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

    static function getExitConceptName($concept){
        switch ($concept){
            case 'MOF': return 'Misión Oficial';break;
            case 'IAC': return 'Intercambio Académico';break;
            case 'IES': return 'Intercambio Estudiantil';break;
            case 'ICU': return 'Intercambio Cultural';break;
            case 'EVE': return 'Evento';break;
            case 'BMA': return 'Beca Maestría';break;
            case 'BPR': return 'Beca Predoctoral';break;
            case 'BPO': return 'Beca Postdoctoral';break;
            case 'PIN': return 'Proyecto Internacional';break;
            case 'ATE': return 'Asistencia Técnica Exportada';break;
            case 'COM': return 'Comercialización';break;
            default: return '';break;
        }
    }
}