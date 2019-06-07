<?php

namespace DRI\ForeingStudentBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Filter\Select2Filter;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

/**
 * Class PostgraduateDatatable
 *
 * @package DRI\ForeingStudentBundle\Datatables
 */
class PostgraduateDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function ($line) use ($router) {
            //$route = $router->generate('profile_show', array('id' => $line['createdBy']['id']));
            $line['courseType'] = $this->formatType($line['courseType']);
            $line['gender'] = $this->formatGender($line['gender']);
            $line['country']['spName'] = $this->formatCountry($line['country']['iso3']);
            $line['course']['name'] = $this->formatCourse($line['course']['name'], $line['shortCourse']);

            return $line;
        };

        return $formatter;
    }


    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            //'cdn_language_by_locale' => true
            'language' => 'es'
        ));

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'classes' => Style::BASE_STYLE_COMPACT,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
            'order_classes' => false,
            'scroll_collapse' => true,
            "order" => [
                [10, "desc"]
            ],
            //'dom' => "<'row' <'col-md-12 pull-left' >> lfrtip",
            'dom' => "<'row'<'col-md-6 col-sm-12'pli><'col-md-6 col-sm-12' <'table-group-actions pull-right'><>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
            "length_menu"=> [
                [10, 50, 100, 150, 200, -1],
                [10, 50, 100, 150, 200, "Todo"] // change per page values here
            ],
            "paging_type" => "bootstrap_extended",
        ));

        $this->features->set(array(
            'paging'     => true,
            'length_change' => true,
            'auto_width' => true,
            //'scroll_x'  => true,
            //'scroll_y'  => 300,
            //'defer_render'  => true,
        ));

        $this->extensions->set(array(
            'responsive' => true,
            //'buttons' => true,
            'buttons' => array(
                //'show_buttons' => array('copy', 'print'),
                'create_buttons' => array(
                    array(
                        'extend'    => 'print',
                        'class_name' => 'btn default',
                        'text'      => 'Imprimir',
                    ),
                    array(
                        'extend'    => 'copy',
                        'class_name' => 'btn default',
                        'text'      => 'Copiar',
                    ),
                    array(
                        'extend'    => 'pdf',
                        'class_name' => 'btn default',
                        'button_options' => array(
                            'download' => 'open',
                            'exportOptions' => array(
                                'columns' => array('1','2', '3', '4', '5','6','7'),
                            ),
                            'orientation' => 'landscape',
                            'pageSize' => 'LEGAL',
                            'message' => 'PDF creado por la SIGESDRI.'
                        ),
                    ),
                    array(
                        'extend'    => 'excel',
                        'class_name' => 'btn default',
                    ),
                    array(
                        'extend'    => 'csv',
                        'class_name' => 'btn default',
                    ),
                    array(
                        'text'      => 'Recargar',
                        'class_name' => 'btn default',
                        'action' => [
                            'template' =>':extension:reload.js.twig'
                        ],
                    ),
                ),
            ),
        ));

        $this->callbacks->set(array(
            'init_complete' => array(
                'template' => ':callback:init.js.twig',
            ),
        ));

        $this->events->set(array(
            'pre_xhr' => array(
                'template' => ':event:pre_xhr.js.twig',
                'vars' => array('table_name' => $this->getName()),
            ),
            'xhr' => array(
                'template' => ':event:xhr.js.twig',
                'vars' => array('table_name' => $this->getName()),
            ),
        ));

        $postgraduates = $this->em->getRepository('DRIForeingStudentBundle:Postgraduate')->findAll();
        $countries = $this->em->getRepository('DRIUsefulBundle:Country')->findAll();
        $courses = $this->em->getRepository('DRIUsefulBundle:Course')->findAll();

        $this->columnBuilder
            ->add(null,MultiselectColumn::class, array(
                'start_html' => '<div class="start_checkboxes">',
                'end_html' => '</div>',
                'add_if' => function () {
                    return $this->authorizationChecker->isGranted('ROLE_ADMIN');
                },
                'value' => 'id',
                'value_prefix' => true,
                'width' => '2%',
                //'render_actions_to_id' => 'table-actions',
                'actions' => array(
                    array(
                        'button_value' => 'postgraduate_bulk_delete',
                        'icon' => 'glyphicon glyphicon-ok',
                        'label' => 'Borrar',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Borrar',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                        ),
                        'button' => true,
                        'confirm' => true,
                        'confirm_message' => 'Está seguro?',
                        /*'start_html' => '<div class="start_delete_action">',
                        'end_html' => '</div>',*/
                        'render_if' => function () {
                            return $this->authorizationChecker->isGranted('ROLE_ADMIN');
                        },
                    )),
            ))
            ->add('country.spName', Column::class, array(
                'title' => 'País',
                'searchable' => true,
                'orderable' => true,
                'width' => '10%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todas') + $this->getOptionsArrayFromEntities($countries, 'spName', 'spName'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('country.iso3', Column::class, array(
                'visible' => false,
            ))
            ->add('ci', Column::class, array(
                'title' => 'CI',
                'searchable' => true,
                'orderable' => true,
                'width' => '10%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($postgraduates, 'ci', 'ci'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('fullName', Column::class, array(
                'title' => 'Nombre',
                'searchable' => true,
                'orderable' => true,
                'width' => '14%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($postgraduates, 'fullName', 'fullName'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('fullNameSlug', Column::class, array(
                'visible' => false,
            ))
            ->add('gender', Column::class, array(
                'title' => 'Género',
                'width' => '10%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Ambos',
                            'F' => 'Femenino',
                            'M' => 'Masculino',
                        ),
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
            ))
            ->add('courseType', Column::class, array(
                'title' => 'Tipo de curso',
                'width' => '10%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'multiple' => false,
                        'select_options' => array(
                            ''    => 'Todos',
                            'DOC' => 'Doctorado',
                            'MAE' => 'Maestría',
                            'ESP' => 'Especialidad',
                            'CCO' => 'Curso Corto'
                        ),
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
            ))
            ->add('course.name', Column::class, array(
                'title' => 'Curso',
                'searchable' => true,
                'orderable' => true,
                'width' => '14%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todas') + $this->getOptionsArrayFromEntities($courses, 'name', 'name') + $this->getOptionsArrayFromEntities($postgraduates, 'shortCourse', 'shortCourse'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('shortCourse', Column::class, array(
                'visible' => false,
            ))
            ->add('expiryDate', DateTimeColumn::class, array(
                'title' => 'Expira',
                'default_content' => 'No Asignado',
                'date_format' => 'LL',
                'width' => '10%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),

                'actions' => array(
                    array(
                        'route' => 'postgraduate_show',
                        'route_parameters' => array(
                            'fullNameSlug' => 'fullNameSlug'
                        ),
                        'icon' => 'la la-ellipsis-h font-lg',
                        'attributes' => array(
                            //'rel' => 'tooltip',
                            //'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-circle green btn-icon-only btn-outline',
                            'role' => 'button',
                            'data-container' => 'body',
                            'data-placement' => 'bottom',
                            'data-original-title' => $this->translator->trans('sg.datatables.actions.show'),
                        ),
                    ),
                    array(
                        'route' => 'postgraduate_edit',
                        'route_parameters' => array(
                            'fullNameSlug' => 'fullNameSlug'
                        ),

                        'icon' => 'la la-edit font-lg',
                        'attributes' => array(
                            //'rel' => 'tooltips',
                            //'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-circle green btn-icon-only btn-outline',
                            'role' => 'button',
                            'data-container' => 'body',
                            'data-placement' => 'bottom',
                            'data-original-title' => $this->translator->trans('sg.datatables.actions.edit'),
                        ),
                    )
                )
            ))
        ;
    }

    /**
     * Determinate type
     *
     * @param string $type
     *
     * @return string
     */
    private function formatType($type){
        if($type == 'DOC'){
            return 'Doctorado';
        }elseif ($type == 'MAE'){
            return 'Maestría';
        }elseif ($type == 'ESP'){
            return 'Especialidad';
        }elseif ($type == 'CCO'){
            return 'Curso Corto';
        }else{
            return '-';
        }
    }

    /**
     * Determinate gender
     *
     * @param string $gender
     *
     * @return string
     */
    private function formatGender($gender){
        if($gender == 'F'){
            return '<i class="fa fa-venus font-red-pink sbold"></i> Femenino';
        }elseif ($gender == 'M'){
            return '<i class="fa fa-mars font-blue sbold"></i> Masculino';
        }else{
            return '-';
        }
    }

    /**
     * Determinate Country
     *
     * @param string $iso3
     *
     * @return string
     */
    private function formatCountry($iso3){

        $pathPackage = new PathPackage('/assets/global/img/countries_flags/', new StaticVersionStrategy('v1'));

        $em = $this->getEntityManager();
        $country = $em->getRepository('DRIUsefulBundle:Country')->findOneBy(['iso3'],$iso3);
        $compound = '';

        if($country){
            $flag = $pathPackage->getUrl($iso3.'.png');

            $compound = '<img class="flag" src="'.$flag.'" />  '.$country->getSpName();
        }
        return $compound;
    }

    /**
     * Determinate Country
     *
     * @param string $course
     * @param string $shortCourse
     *
     * @return string
     */
    private function formatCourse($course, $shortCourse){

        if ($course){
            return $course;
        }elseif ($shortCourse){
            return $shortCourse;
        }else{
            return '-';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'DRI\ForeingStudentBundle\Entity\Postgraduate';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'postgraduate_datatable';
    }
}
