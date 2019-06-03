<?php

namespace DRI\ExitBundle\Datatables;

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
 * Class DepartureDatatable
 *
 * @package DRI\ExitBundle\Datatables
 */
class DepartureDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function ($line) use ($router) {
            //$route = $router->generate('profile_show', array('id' => $line['createdBy']['id']));
            //$line['reason'] = $this->formatReason($line['reason']);
            //$line['concept'] = $this->formatConcept($line['concept']);
            //$line['state'] = $this->formatState($line['state']);
            $line['closed'] = $this->formatState($line['closed']);

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
            'classes' => Style::BASE_STYLE_COMPACT,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
            'order_classes' => false,
            'scroll_collapse' => true,
            "order" => [
                [3, "desc"]
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
                                'columns' => array('1','2', '3', '4'),
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

        $departures = $this->em->getRepository('DRIExitBundle:Departure')->findAll();
        $clients = $this->em->getRepository('DRIClientBundle:Client')->findAll();
        $passports = $this->em->getRepository('DRIPassportBundle:Passport')->findAll();
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
                            'button_value' => 'departure_bulk_delete',
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
            ->add('client.fullName', Column::class, array(
                'title' => 'Cliente',
                'searchable' => true,
                'orderable' => true,
                'width' => '16%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($clients, 'fullName', 'fullName'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('passport.number', Column::class, array(
                'title' => 'Pasaporte',
                'searchable' => true,
                'orderable' => true,
                'width' => '16%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($passports, 'number', 'number'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('departureDate', DateTimeColumn::class, array(
                'title' => 'Fecha de Partida',
                'date_format' => 'LL',
                'width' => '16%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
            ))
            ->add('returnDate', DateTimeColumn::class, array(
                'title' => 'Fecha de Retorno',
                'default_content' => '--',
                'date_format' => 'LL',
                'width' => '16%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
            ))
            ->add('closed', Column::class, array(
                'title' => 'Estado',
                'searchable' => true,
                'orderable' => true,
                'width' => '16%',
                'default_content' => 'No asignado',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array(
                        '' => 'Todos',
                        '1' => 'Culminada',
                        '0' => 'En Ejecución'
                    ),
                    'cancel_button' => true,
                    'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                ))
            ))
            ->add('numberSlug', Column::class, array(
                'visible' => false,
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),

                'actions' => array(
                    array(
                        'route' => 'departure_show',
                        'route_parameters' => array(
                            'numberSlug' => 'numberSlug'
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
                        'route' => 'departure_edit',
                        'route_parameters' => array(
                            'numberSlug' => 'numberSlug'
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
     * Determinate state
     *
     * @param boolean $state
     *
     * @return string
     */
    private function formatState($state){
        $type = '<span class="font-green-meadow sbold"><i class="la la-paper-plane-o"></i> En Ejecución </span>';

        if($state)
            $type = '<span class="font-red-thunderbird sbold"><i class="la la-archive"></i> Culminada </span>';

        return $type;
    }


    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'DRI\ExitBundle\Entity\Departure';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'departure_datatable';
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
