<?php

namespace Dri\TramitesBundle\Datatables;

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

/**
 * Class PasaporteDatatable
 *
 * @package Dri\TramitesBundle\Datatables
 */
class PasaporteDatatable extends AbstractDatatable
{
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
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            //'global_search_type' => 'gt',
            'search_in_non_visible_columns' => true,
            //"dom" => "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
        ));

        $this->features->set(array(
            //'info' => true,
            //'paging' => false,
            //'searching' => false,
        ));

        $this->columnBuilder
            ->add(
                null,
                MultiselectColumn::class,
                array(
                    'start_html' => '<div class="start_checkboxes">',
                    'end_html' => '</div>',
                    'value' => 'id',
                    'value_prefix' => true,
                    //'render_actions_to_id' => 'sidebar-multiselect-actions',
                    'actions' => array(
                        array(
                            'route' => 'pasaporte_bulk_action',
                            'icon' => 'glyphicon glyphicon-ok',
                            'label' => 'Borrar Pasaportes',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Borrar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button',
                            ),
                            'confirm' => true,
                            'confirm_message' => 'Estas seguro?',
                            'start_html' => '<div class="start_delete_action">',
                            'end_html' => '</div>',
                        ),
                    ),
                )
            )
            ->add('noPas', Column::class, array(
                'title' => 'Número',
                'filter' => array(TextFilter::class, array(
                    'cancel_button' => true,
                    'classes' => 'form-control form-filter input-sm',
                    'search_type' => 'eq',
                    'placeholder' => false,
                )),
                'width' => '100px',
                ))
            ->add('tipoPas', Column::class, array(
                'title' => 'Tipo',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'multiple' => true,
                        'select_options' => array(
                            'OFI' => 'Oficial',
                            'ORD' => 'Ordinario',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control form-filter input-sm bs-select',
                    ),
                ),
                'width' => '20%',
            ))
            ->add('titular.nombreCompleto', Column::class, array(
                'title' => 'Titular',
                'filter' => array(TextFilter::class,
                    array(
                        'cancel_button' => true,
                        'classes' => 'form-control form-filter input-sm',
                        'search_type' => 'eq',
                        'placeholder' => false,
                    ),
                ),
                'width' => '200px',
            ))
            ->add('estadoPas', Column::class, array(
                'title' => 'Estado',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'cancel_button' => true,
                        'multiple' => true,
                        'select_options' => array(
                            'ACT' => 'Activo',
                            'INA' => 'Inactivo',
                            'BAJ' => 'Baja'
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control form-filter input-sm bs-select',
                    ),
                ),
                'width' => '150px',
            ))
            ->add('fechaExp', DateTimeColumn::class, array(
                'title' => 'Expedido',
                'default_content' => 'No value',
                'date_format' => 'L',
                'filter' => array(DateRangeFilter::class, array(
                    'cancel_button' => true,
                    'classes' => 'form-control form-filter input-sm',
                    'placeholder' => false,
                )),
                //'timeago' => true,
                'width' => '160px',
                ))
            ->add('fechaVen', DateTimeColumn::class, array(
                'title' => 'Vencimiento',
                'default_content' => 'No value',
                'date_format' => 'L',
                'filter' => array(DateRangeFilter::class, array(
                    'cancel_button' => true,
                    'classes' => 'form-control form-filter input-sm',
                    'placeholder' => false,
                )),
                //'timeago' => true,
                'width' => '160px',
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'pasaporte_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Ver',
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'pasaporte_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Editar',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'Dri\TramitesBundle\Entity\Pasaporte';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pasaporte_datatable';
    }
}
