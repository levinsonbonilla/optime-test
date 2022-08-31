<?php

namespace App\Handler;

use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Customer;
use DateTime;

class Utils
{
    private $translator;
    private $secure;    

    public function __construct(TranslatorInterface $translator = null, Security $secure=null){
        $this->translator = $translator;
        $this->secure = $secure;
    }

    /* Convert to boolean from a 0 or a 1 */
    public static function convertBollean($data){
        return (($data == 1)?true:false);
    }

    /* Remove accents of string */
    public static function removeAccents($string){
                
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string );
            
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string );
            
            $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string );
                
        // $string = str_replace(
            //     array('ñ', 'Ñ', 'ç', 'Ç'),
            //     array('n', 'N', 'c', 'C'),
            //     $string
        // );
        $string = utf8_encode($string);
        
        return $string;
    }

    /*Replace space to mod (%)*/
    public static function replaceSpaces($data){
        $data = str_replace(
            ' ',
            '%',
            $data
        );
        return $data;
    }

    /* filters to datatable */
    public function filtersDataTable($query,$data,$columns,$isCount = false,$isBetween = false){
        extract($data);
        $filters = ((empty($filterValue["value"]))?[]:json_decode($filterValue["value"],true));

        foreach ($filters as $key => $filter) {  
            $condition = "and";
            $value = "";
            $nameColumn = explode(".",$columns[$key]);
            $isPending = true;
            
            if (is_array($filter)) {
                $value = $filter["value"];
                $condition = $filter["condition"];
            }else {
                $value = $filter;
            }

            /* condition to active */
            if(end($nameColumn)  == "active" && $value != "2" && $isPending){
                $query
                ->andWhere($columns[$key]." = :filterValue$key")
                ->setParameter("filterValue$key", Utils::convertBollean($value));
                $isPending = false;
            }

            $isPending = ((end($nameColumn)  == "active")?false:true);


            /* condition "and" */
            if (!empty($value) && $condition == "and" && $isPending) {
                $query
                ->andWhere("UPPER(".$columns[$key].") LIKE :filterValue$key")
                ->setParameter("filterValue$key", '%'.trim(mb_strtoupper($value)).'%');
                $isPending = false;
            }

            /* condition "or" */
            if (!empty($value) && $condition == "or" && $isPending) {
                $query
                ->orWhere("REPLACE_ACCENTS(UPPER(".$columns[$key].")) LIKE :filterValue$key")
                ->setParameter("filterValue$key", '%'.trim(mb_strtoupper($value)).'%');
                $isPending = false;
            }

        }

        /* This content is executed when the search returns columns and not just the record count */
        if (!$isCount && is_array($order)) {
            
            if ((intval(reset($order)["column"])+1) <= count($columns)) {
                $query
                ->orderBy($columns[reset($order)["column"]], reset($order)["dir"]);
            }
            $query
            ->setFirstResult($start);

            if (intval($length) > 0) {
                $query
                ->setMaxResults($length);               
            }
        }

        return $query;
    }    

    /* change language */
    public function changeLanguage($request, $lang, $isChosenByUser) {
        $lang = ($lang == 'es') ? $lang : 'en';
        $translator = $this->translator;
        if ($isChosenByUser || !$request->getSession()->get('is_chosen_by_user', false)) {
            $request->getSession()->set('_locale', $lang);
            $request->getSession()->set('is_chosen_by_user', $isChosenByUser);
            $request->getSession();
            $request->setLocale($lang);
            $translator->setLocale($lang);
        }
    }

    /* Return enable or disable from boolean */
    public function getFormatStatus($status,$isRounderPill){
        $rounderPill = (($isRounderPill)?"rounded-pill":"");

        $statusReturn = '<span class="badge '.$rounderPill.' bg-danger">'.$this->translator->trans('no').'</span>';
        if ($status) {
            $statusReturn = '<span class="badge '.$rounderPill.' bg-info bg-primary">'.$this->translator->trans('yes').'</span>';
        }
        return $statusReturn;
    }

}