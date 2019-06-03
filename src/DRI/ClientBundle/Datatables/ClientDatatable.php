<?php

namespace DRI\ClientBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;
use Sg\DatatablesBundle\Datatable\Filter\Select2Filter;

use DRI\ClientBundle\Entity\Client;

//use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;


/**
 * Class ClientDatatable
 *
 * @package DRI\ClientBundle\Datatables
 */
class ClientDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function ($line) use ($router) {
            //$route = $router->generate('profile_show', array('id' => $line['createdBy']['id']));
            $line['clientPicture'] = $this->formatClientPicture($line['clientPicture'],$line['gender']);
            $line['gender'] = $this->formatGender($line['gender']);
            $line['clientType'] = $this->formatClientType($line['clientType']);
            $line['faculty']['name'] = $this->formatFacultyName($line['faculty']['name']);

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            //'cdn_language_by_locale' => true
            'language' => 'es'

        ));

        $this->ajax->set(array());

        $this->options->set(array(
            'classes' => Style::BASE_STYLE_COMPACT,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
            'order_classes' => false,
            'scroll_collapse' => true,
            "order" => [
                [7, "desc"]
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
                                'columns' => array('1','2', '3', '4', '5', '6',),
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

        $area = $this->em->getRepository('DRIUsefulBundle:Area')->findAll();
        $clients = $this->em->getRepository('DRIClientBundle:Client')->findAll();


        $this->columnBuilder
            ->add(null, MultiselectColumn::class, array(
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
                            'button_value' => 'client_bulk_delete',
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
                        )
                    ),
                ))
            ->add('clientPicture', ImageColumn::class, array(
                'title' => 'Fotografía',
                'searchable' => false,
                'orderable' => false,
                'width' => '5%',
                'imagine_filter' => 'thumbnail_50_x_50',
                'imagine_filter_enlarged' => 'client_250_x_250',
                'relative_path' => '/images/clients',
                'enlarge' => true,
            ))
            ->add('ci', Column::class, array(
                'title' => 'CI',
                'width' => '13%',
                'filter' => array(TextFilter::class, array(
                    'cancel_button' => true,
                    'classes' => 'form-control',
                    'search_type' => 'like',
                    'placeholder' => false,
                )),
                ))
            ->add('fullName', Column::class, array(
                'title' => 'Nombre del Cliente',
                'searchable' => true,
                'orderable' => true,
                'width' => '13%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($clients, 'fullName', 'fullName'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('gender', Column::class, array(
                'title' => 'Género',
                'width' => '13%',
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
            ->add('clientType', Column::class, array(
                'title' => 'Tipo',
                'width' => '13%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'multiple' => false,
                        'select_options' => [''    => 'Todos'] + Client::CLIENT_TYPES,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
            ))
            ->add('faculty.name', Column::class, array(
                'title' => 'Facultad',
                'searchable' => true,
                'orderable' => true,
                'default_content' => 'No asignado',
                'width' => '13%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todas') + $this->getOptionsArrayFromEntities($area, 'name', 'name'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control',
                )),
            ))
            ->add('createdAt', DateTimeColumn::class, array(
                'title' => 'Creado',
                'width' => '13%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                        'placeholder_text' => 'Creado ...'
                    ),
                ),
                'timeago' => true,
            ))
            ->add('fullNameSlug', Column::class, array(
                'visible' => false,
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),

                'actions' => array(
                    array(
                        'route' => 'client_profile',
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
                        'route' => 'client_config',
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
     * Format clientType
     *
     * @param string $clientType
     *
     * @return string
     */
    private function formatClientType($clientType){
        return Client::clientType_AcronimToName($clientType);
    }

    /**
     * Format clientPicture
     *
     * @param string $clientPicture
     * @param string $gender
     *
     * @return string
     */
    private function formatClientPicture($clientPicture, $gender){
        if($clientPicture == null){
            if ($gender == 'F')
                return 'profile-female.png';
            elseif ($gender == 'M')
                return 'profile-male.png';
        }else{
            return $clientPicture;
        }
    }

    /**
     * Format clientPicture
     *
     * @return string
     */
    private function formatFacultyName($facultyName){
        if($facultyName == null){
            return '<span class="font-red sbold"><i class="la la-calendar-times-o"></i> No Asignado</span>';
        }else{
            return $facultyName;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'DRI\ClientBundle\Entity\Client';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'client';
    }


    /**
     * Get User.
     *
     * @return mixed|null
     */
    private function getUser()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->securityToken->getToken()->getUser();
        } else {
            return null;
        }
    }

    /**
     * Is admin.
     *
     * @return bool
     */
    private function isAdmin()
    {
        return $this->authorizationChecker->isGranted('ROLE_ADMIN');
    }

    /**
     * Get default order col.
     *
     * @return int
     */
    private function getDefaultOrderCol()
    {
        return true === $this->isAdmin()? 1 : 0;
    }

    /**
     * Returns the columns which are to be displayed in a pdf.
     *
     * @return array
     */
    private function getPdfColumns()
    {
        if (true === $this->isAdmin()) {
            return array(
                '1', // id column
                '2', // title column
                '3', // visible column
            );
        } else {
            return array(
                '0', // id column
                '1', // title column
                '2', // visible column
            );
        }
    }
}
