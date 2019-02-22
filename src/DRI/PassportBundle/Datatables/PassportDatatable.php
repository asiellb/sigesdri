<?php

namespace DRI\PassportBundle\Datatables;

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

/**
 * Class PassportDatatable
 *
 * @package DRI\PassportBundle\Datatables
 */
class PassportDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function ($line) use ($router) {
            //$route = $router->generate('profile_show', array('id' => $line['createdBy']['id']));
            $line['type'] = $this->formatType($line['type']);
            $line['state'] = $this->formatState($line['state']);
            //$line['state'] = $this->formatState($line['state']);

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

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
            "order" => [
                [2, "asc"]
            ],
            //'dom' => "<'row' <'col-md-12 pull-left' >> lfrtip",
            'dom' => "<'row'<'col-md-6 col-sm-12'pli><'col-md-6 col-sm-12' <'table-group-actions pull-right'><>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
            "length_menu"=> [
                [5, 10, 20, 50, 100, 150, -1],
                [5, 10, 20, 50, 100, 150, "All"] // change per page values here
            ],
            "paging_type" => "bootstrap_extended",
        ));

        $this->features->set(array(
            'paging'     => true,
            'length_change' => true,
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

        $passports = $this->em->getRepository('DRIPassportBundle:Passport')->findAll();
        $clients = $this->em->getRepository('DRIClientBundle:Client')->findAll();
        $users = $this->em->getRepository('DRIUserBundle:User')->findAll();

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
                        'button_value' => 'passport_bulk_delete',
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
            ->add('number', Column::class, array(
                'title' => 'Número',
                'searchable' => true,
                'orderable' => true,
                'width' => '10%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($passports, 'number', 'number'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('issueDate', DateTimeColumn::class, array(
                'title' => 'Emitido',
                'width' => '20%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
            ))
            ->add('expiryDate', DateTimeColumn::class, array(
                'title' => 'Vence',
                'width' => '20%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
            ))
            ->add('type', Column::class, array(
                'title' => 'Tipo',
                'width' => '2%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Ambos',
                            'OFI' => 'Oficial',
                            'ORD' => 'Ordinario',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
                ))
            ->add('state', Column::class, array(
                'title' => 'Estado',
                'width' => '2%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Todos',
                            'ACT' => 'Activo',
                            'VEN' => 'Vencido',
                            'BAJ' => 'Baja',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
                ))
            ->add('createdAt', DateTimeColumn::class, array(
                'title' => 'Creado',
                'width' => '20%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
                'timeago' => true,
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'passport_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'icon-eye',
                        'attributes' => array(
                            //'rel' => 'tooltip',
                            //'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'tooltips btn blue btn-outline btn-sm ',
                            'role' => 'button',
                            'data-container' => 'body',
                            'data-placement' => 'bottom',
                            'data-original-title' => $this->translator->trans('sg.datatables.actions.show'),
                        ),
                    ),
                    array(
                        'route' => 'passport_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),

                        'icon' => 'icon-settings',
                        'attributes' => array(
                            //'rel' => 'tooltips',
                            //'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'tooltips btn blue btn-outline btn-sm',
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
        if($type == 'OFI'){
            return 'Oficial';
        }elseif ($type == 'ORD'){
            return 'Ordinario';
        }else{
            return '-';
        }
    }

    /**
     * Determinate state
     *
     * @param string $state
     *
     * @return string
     */
    private function formatState($state){
        $type = '';
        switch ($state){
            case 'ACT':
                $type = 'Activo'; break;
            case 'VEN':
                $type = 'Vencido'; break;
            case 'BAJ':
                $type = 'Baja'; break;
            default:
                $type = '-'; break;
        }
        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'DRI\PassportBundle\Entity\Passport';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'passport_datatable';
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
