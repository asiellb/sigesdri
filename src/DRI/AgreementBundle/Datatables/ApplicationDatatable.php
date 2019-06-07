<?php

namespace DRI\AgreementBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Filter\Select2Filter;

use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

/**
 * Class ApplicationDatatable
 *
 * @package DRI\AgreementBundle\Datatables
 */
class ApplicationDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;

        $formatter = function ($line) use ($router) {
            //$route = $router->generate('profile_show', array('id' => $line['createdBy']['id']));
            $line['institution']['country']['spName'] = $this->formatCountry($line['institution']['country']['iso3']);
            $line['institution']['name'] = $this->formatInstitution($line['institution']['name'], $line['institution']['url']);
            //$line['digitalCopy'] = $this->formatDC($line['digitalCopy']);
            $line['state'] = $this->formatState($line['state']);
            $line['closed'] = $this->formatUse($line['closed']);
            $line['used'] = $this->formatUse($line['used']);

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

        $applications = $this->em->getRepository('DRIAgreementBundle:Application')->findAll();
        $institutions = $this->em->getRepository('DRIAgreementBundle:Institution')->findAll();
        $countries = $this->em->getRepository('DRIUsefulBundle:Country')->findAll();

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
                        'button_value' => 'agreement_application_bulk_delete',
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
            ->add('number', Column::class, array(
                'title' => 'Número #',
                'searchable' => true,
                'orderable' => true,
                'width' => '14%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todos') + $this->getOptionsArrayFromEntities($applications, 'number', 'number'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
                ))
            ->add('numberSlug', Column::class, array(
                'visible' => false,
                ))
            ->add('institution.country.iso3', Column::class, array(
                'visible' => false,
            ))
            ->add('institution.country.spName', Column::class, array(
                'title' => 'País',
                'searchable' => true,
                'orderable' => true,
                'width' => '9%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todas') + $this->getOptionsArrayFromEntities($countries, 'spName', 'spName'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('institution.logo', ImageColumn::class, array(
                'title' => null,
                'searchable' => false,
                'orderable' => false,
                'width' => '5%',
                'imagine_filter' => 'thumbnail_50_x_50',
                'imagine_filter_enlarged' => 'client_250_x_250',
                'relative_path' => '/images/institutions_logos',
                'enlarge' => true,
            ))
            ->add('institution.name', Column::class, array(
                'title' => 'Institución',
                'searchable' => true,
                'orderable' => true,
                'width' => '20%',
                'filter' => array(Select2Filter::class, array(
                    'select_options' => array('' => 'Todas') + $this->getOptionsArrayFromEntities($institutions, 'name', 'name'),
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'classes' => 'form-control'
                )),
            ))
            ->add('institution.url', Column::class, array(
                'visible' => false,
            ))
            ->add('createdAt', DateTimeColumn::class, array(
                'title' => 'Creada',
                'width' => '10%',
                'filter' => array(DateRangeFilter::class,
                    array(
                        'cancel_button' => true,
                    ),
                ),
                'timeago' => true,
            ))
            ->add('state', Column::class, array(
                'title' => 'Estado',
                'width' => '10%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Todos',
                            'CON' => 'Confeccionada',
                            'APR' => 'Aprobada',
                            'REC' => 'Rechazada',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
                ))
            ->add('closed', Column::class, array(
                'title' => 'Cerrada',
                'width' => '10%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Todos',
                            1 => 'Si',
                            0 => 'No',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
            ))
            ->add('used', Column::class, array(
                'title' => 'Usada',
                'width' => '10%',
                'filter' => array(SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'multiple' => false,
                        'select_options' => array(
                            '' => 'Todos',
                            1 => 'Si',
                            0 => 'No',
                        ),
                        'cancel_button' => true,
                        'classes' => 'form-control input-xs input-sm input-inline form-filter bs-select',
                    ),
                ),
            ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),

                'actions' => array(
                    array(
                        'route' => 'agreement_application_show',
                        'route_parameters' => array(
                            'id' => 'id'
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
                        'route' => 'agreement_application_edit',
                        'route_parameters' => array(
                            'id' => 'id'
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
     * Format URL
     *
     * @param string $institution
     * @param string $url
     *
     * @return string
     */
    private function formatInstitution($institution, $url){
        if($url){
            if (preg_match('#^(https?://)#i', $url) === 1){
                $compound = '<a href="'.$url.'" target="_blank">'.$institution.'</a>';
            } else {
                $compound = '<a href="http://'.$url.'" target="_blank">'.$institution.'</a>';
            }
        }else{
            $compound = $institution;
        }

        return $compound;
    }

    /**
     * Determinate state
     *
     * @param string $state
     *
     * @return string
     */
    private function formatState($state){
        if($state){
            switch ($state){
                case 'CON':
                    $state = '<div class="alert alert-info sbold"> Confeccionada </div>'; break;
                case 'APR':
                    $state = '<div class="alert alert-success sbold"> Aprobada </div>'; break;
                case 'REC':
                    $state = '<div class="alert alert-danger sbold"> Rechazada </div>'; break;
                default:
                    $state = '-'; break;
            }
        }
        return $state;
    }

    /**
     * Determinate state
     *
     * @param boolean $use
     *
     * @return string
     */
    private function formatUse($use){

        if ($use) {
            $used = '<div class="font-green sbold"> Si </div>';
        }else{
            $used = '<div class="font-red-mint sbold"> No </div>';
        }

        return $used;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'DRI\AgreementBundle\Entity\Application';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'agreement_application_datatable';
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
