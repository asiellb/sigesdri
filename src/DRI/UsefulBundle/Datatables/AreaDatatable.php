<?php

namespace DRI\UsefulBundle\Datatables;

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
 * Class AreaDatatable
 *
 * @package DRI\UsefulBundle\Datatables
 */
class AreaDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        ));

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));

        $this->features->set(array(
        ));

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                ))
            ->add('name', Column::class, array(
                'title' => 'Name',
                ))
            ->add('leader', Column::class, array(
                'title' => 'Leader',
                ))
            ->add('students.id', Column::class, array(
                'title' => 'Students Id',
                'data' => 'students[, ].id'
                ))
            ->add('students.ci', Column::class, array(
                'title' => 'Students Ci',
                'data' => 'students[, ].ci'
                ))
            ->add('students.firstName', Column::class, array(
                'title' => 'Students FirstName',
                'data' => 'students[, ].firstName'
                ))
            ->add('students.secondName', Column::class, array(
                'title' => 'Students SecondName',
                'data' => 'students[, ].secondName'
                ))
            ->add('students.firstLastName', Column::class, array(
                'title' => 'Students FirstLastName',
                'data' => 'students[, ].firstLastName'
                ))
            ->add('students.secondLastName', Column::class, array(
                'title' => 'Students SecondLastName',
                'data' => 'students[, ].secondLastName'
                ))
            ->add('students.fullName', Column::class, array(
                'title' => 'Students FullName',
                'data' => 'students[, ].fullName'
                ))
            ->add('students.fullNameSlug', Column::class, array(
                'title' => 'Students FullNameSlug',
                'data' => 'students[, ].fullNameSlug'
                ))
            ->add('students.birthday', Column::class, array(
                'title' => 'Students Birthday',
                'data' => 'students[, ].birthday'
                ))
            ->add('students.gender', Column::class, array(
                'title' => 'Students Gender',
                'data' => 'students[, ].gender'
                ))
            ->add('students.email', Column::class, array(
                'title' => 'Students Email',
                'data' => 'students[, ].email'
                ))
            ->add('students.foreignEmail', Column::class, array(
                'title' => 'Students ForeignEmail',
                'data' => 'students[, ].foreignEmail'
                ))
            ->add('students.privatePhone', Column::class, array(
                'title' => 'Students PrivatePhone',
                'data' => 'students[, ].privatePhone'
                ))
            ->add('students.cellPhone', Column::class, array(
                'title' => 'Students CellPhone',
                'data' => 'students[, ].cellPhone'
                ))
            ->add('students.clientType', Column::class, array(
                'title' => 'Students ClientType',
                'data' => 'students[, ].clientType'
                ))
            ->add('students.clientPicture', Column::class, array(
                'title' => 'Students ClientPicture',
                'data' => 'students[, ].clientPicture'
                ))
            ->add('students.languages', Column::class, array(
                'title' => 'Students Languages',
                'data' => 'students[, ].languages'
                ))
            ->add('students.organizations', Column::class, array(
                'title' => 'Students Organizations',
                'data' => 'students[, ].organizations'
                ))
            ->add('students.mothersName', Column::class, array(
                'title' => 'Students MothersName',
                'data' => 'students[, ].mothersName'
                ))
            ->add('students.fathersName', Column::class, array(
                'title' => 'Students FathersName',
                'data' => 'students[, ].fathersName'
                ))
            ->add('students.civilState', Column::class, array(
                'title' => 'Students CivilState',
                'data' => 'students[, ].civilState'
                ))
            ->add('students.weight', Column::class, array(
                'title' => 'Students Weight',
                'data' => 'students[, ].weight'
                ))
            ->add('students.height', Column::class, array(
                'title' => 'Students Height',
                'data' => 'students[, ].height'
                ))
            ->add('students.eyesColor', Column::class, array(
                'title' => 'Students EyesColor',
                'data' => 'students[, ].eyesColor'
                ))
            ->add('students.skinColor', Column::class, array(
                'title' => 'Students SkinColor',
                'data' => 'students[, ].skinColor'
                ))
            ->add('students.hairColor', Column::class, array(
                'title' => 'Students HairColor',
                'data' => 'students[, ].hairColor'
                ))
            ->add('students.pvs', Column::class, array(
                'title' => 'Students Pvs',
                'data' => 'students[, ].pvs'
                ))
            ->add('students.citizenship', Column::class, array(
                'title' => 'Students Citizenship',
                'data' => 'students[, ].citizenship'
                ))
            ->add('students.stateBirth', Column::class, array(
                'title' => 'Students StateBirth',
                'data' => 'students[, ].stateBirth'
                ))
            ->add('students.cityBirth', Column::class, array(
                'title' => 'Students CityBirth',
                'data' => 'students[, ].cityBirth'
                ))
            ->add('students.foreignCityBirth', Column::class, array(
                'title' => 'Students ForeignCityBirth',
                'data' => 'students[, ].foreignCityBirth'
                ))
            ->add('students.state', Column::class, array(
                'title' => 'Students State',
                'data' => 'students[, ].state'
                ))
            ->add('students.city', Column::class, array(
                'title' => 'Students City',
                'data' => 'students[, ].city'
                ))
            ->add('students.district', Column::class, array(
                'title' => 'Students District',
                'data' => 'students[, ].district'
                ))
            ->add('students.street', Column::class, array(
                'title' => 'Students Street',
                'data' => 'students[, ].street'
                ))
            ->add('students.highway', Column::class, array(
                'title' => 'Students Highway',
                'data' => 'students[, ].highway'
                ))
            ->add('students.firstBetween', Column::class, array(
                'title' => 'Students FirstBetween',
                'data' => 'students[, ].firstBetween'
                ))
            ->add('students.secongBetween', Column::class, array(
                'title' => 'Students SecongBetween',
                'data' => 'students[, ].secongBetween'
                ))
            ->add('students.number', Column::class, array(
                'title' => 'Students Number',
                'data' => 'students[, ].number'
                ))
            ->add('students.km', Column::class, array(
                'title' => 'Students Km',
                'data' => 'students[, ].km'
                ))
            ->add('students.building', Column::class, array(
                'title' => 'Students Building',
                'data' => 'students[, ].building'
                ))
            ->add('students.apartment', Column::class, array(
                'title' => 'Students Apartment',
                'data' => 'students[, ].apartment'
                ))
            ->add('students.cpa', Column::class, array(
                'title' => 'Students Cpa',
                'data' => 'students[, ].cpa'
                ))
            ->add('students.farm', Column::class, array(
                'title' => 'Students Farm',
                'data' => 'students[, ].farm'
                ))
            ->add('students.town', Column::class, array(
                'title' => 'Students Town',
                'data' => 'students[, ].town'
                ))
            ->add('students.area', Column::class, array(
                'title' => 'Students Area',
                'data' => 'students[, ].area'
                ))
            ->add('students.postCode', Column::class, array(
                'title' => 'Students PostCode',
                'data' => 'students[, ].postCode'
                ))
            ->add('students.studentsYear', Column::class, array(
                'title' => 'Students StudentsYear',
                'data' => 'students[, ].studentsYear'
                ))
            ->add('students.studentsPosition', Column::class, array(
                'title' => 'Students StudentsPosition',
                'data' => 'students[, ].studentsPosition'
                ))
            ->add('students.studentsCareer', Column::class, array(
                'title' => 'Students StudentsCareer',
                'data' => 'students[, ].studentsCareer'
                ))
            ->add('students.studentsState', Column::class, array(
                'title' => 'Students StudentsState',
                'data' => 'students[, ].studentsState'
                ))
            ->add('students.studentsLastUpdate', Column::class, array(
                'title' => 'Students StudentsLastUpdate',
                'data' => 'students[, ].studentsLastUpdate'
                ))
            ->add('students.studentsInactiveAt', Column::class, array(
                'title' => 'Students StudentsInactiveAt',
                'data' => 'students[, ].studentsInactiveAt'
                ))
            ->add('students.workersOccupation', Column::class, array(
                'title' => 'Students WorkersOccupation',
                'data' => 'students[, ].workersOccupation'
                ))
            ->add('students.workersSpecialty', Column::class, array(
                'title' => 'Students WorkersSpecialty',
                'data' => 'students[, ].workersSpecialty'
                ))
            ->add('students.workersEduCategory', Column::class, array(
                'title' => 'Students WorkersEduCategory',
                'data' => 'students[, ].workersEduCategory'
                ))
            ->add('students.workersSciGrade', Column::class, array(
                'title' => 'Students WorkersSciGrade',
                'data' => 'students[, ].workersSciGrade'
                ))
            ->add('students.workersPosition', Column::class, array(
                'title' => 'Students WorkersPosition',
                'data' => 'students[, ].workersPosition'
                ))
            ->add('students.workersWorkPlace', Column::class, array(
                'title' => 'Students WorkersWorkPlace',
                'data' => 'students[, ].workersWorkPlace'
                ))
            ->add('students.workersAdmissionDate', Column::class, array(
                'title' => 'Students WorkersAdmissionDate',
                'data' => 'students[, ].workersAdmissionDate'
                ))
            ->add('students.workersWorkPhone', Column::class, array(
                'title' => 'Students WorkersWorkPhone',
                'data' => 'students[, ].workersWorkPhone'
                ))
            ->add('students.workersPay', Column::class, array(
                'title' => 'Students WorkersPay',
                'data' => 'students[, ].workersPay'
                ))
            ->add('students.workersState', Column::class, array(
                'title' => 'Students WorkersState',
                'data' => 'students[, ].workersState'
                ))
            ->add('students.workersLastUpdate', Column::class, array(
                'title' => 'Students WorkersLastUpdate',
                'data' => 'students[, ].workersLastUpdate'
                ))
            ->add('students.workersInactiveAt', Column::class, array(
                'title' => 'Students WorkersInactiveAt',
                'data' => 'students[, ].workersInactiveAt'
                ))
            ->add('students.enabled', Column::class, array(
                'title' => 'Students Enabled',
                'data' => 'students[, ].enabled'
                ))
            ->add('students.locked', Column::class, array(
                'title' => 'Students Locked',
                'data' => 'students[, ].locked'
                ))
            ->add('students.expired', Column::class, array(
                'title' => 'Students Expired',
                'data' => 'students[, ].expired'
                ))
            ->add('students.expiredAt', Column::class, array(
                'title' => 'Students ExpiredAt',
                'data' => 'students[, ].expiredAt'
                ))
            ->add('students.createdAt', Column::class, array(
                'title' => 'Students CreatedAt',
                'data' => 'students[, ].createdAt'
                ))
            ->add('students.updatedAt', Column::class, array(
                'title' => 'Students UpdatedAt',
                'data' => 'students[, ].updatedAt'
                ))
            ->add('workers.id', Column::class, array(
                'title' => 'Workers Id',
                'data' => 'workers[, ].id'
                ))
            ->add('workers.ci', Column::class, array(
                'title' => 'Workers Ci',
                'data' => 'workers[, ].ci'
                ))
            ->add('workers.firstName', Column::class, array(
                'title' => 'Workers FirstName',
                'data' => 'workers[, ].firstName'
                ))
            ->add('workers.secondName', Column::class, array(
                'title' => 'Workers SecondName',
                'data' => 'workers[, ].secondName'
                ))
            ->add('workers.firstLastName', Column::class, array(
                'title' => 'Workers FirstLastName',
                'data' => 'workers[, ].firstLastName'
                ))
            ->add('workers.secondLastName', Column::class, array(
                'title' => 'Workers SecondLastName',
                'data' => 'workers[, ].secondLastName'
                ))
            ->add('workers.fullName', Column::class, array(
                'title' => 'Workers FullName',
                'data' => 'workers[, ].fullName'
                ))
            ->add('workers.fullNameSlug', Column::class, array(
                'title' => 'Workers FullNameSlug',
                'data' => 'workers[, ].fullNameSlug'
                ))
            ->add('workers.birthday', Column::class, array(
                'title' => 'Workers Birthday',
                'data' => 'workers[, ].birthday'
                ))
            ->add('workers.gender', Column::class, array(
                'title' => 'Workers Gender',
                'data' => 'workers[, ].gender'
                ))
            ->add('workers.email', Column::class, array(
                'title' => 'Workers Email',
                'data' => 'workers[, ].email'
                ))
            ->add('workers.foreignEmail', Column::class, array(
                'title' => 'Workers ForeignEmail',
                'data' => 'workers[, ].foreignEmail'
                ))
            ->add('workers.privatePhone', Column::class, array(
                'title' => 'Workers PrivatePhone',
                'data' => 'workers[, ].privatePhone'
                ))
            ->add('workers.cellPhone', Column::class, array(
                'title' => 'Workers CellPhone',
                'data' => 'workers[, ].cellPhone'
                ))
            ->add('workers.clientType', Column::class, array(
                'title' => 'Workers ClientType',
                'data' => 'workers[, ].clientType'
                ))
            ->add('workers.clientPicture', Column::class, array(
                'title' => 'Workers ClientPicture',
                'data' => 'workers[, ].clientPicture'
                ))
            ->add('workers.languages', Column::class, array(
                'title' => 'Workers Languages',
                'data' => 'workers[, ].languages'
                ))
            ->add('workers.organizations', Column::class, array(
                'title' => 'Workers Organizations',
                'data' => 'workers[, ].organizations'
                ))
            ->add('workers.mothersName', Column::class, array(
                'title' => 'Workers MothersName',
                'data' => 'workers[, ].mothersName'
                ))
            ->add('workers.fathersName', Column::class, array(
                'title' => 'Workers FathersName',
                'data' => 'workers[, ].fathersName'
                ))
            ->add('workers.civilState', Column::class, array(
                'title' => 'Workers CivilState',
                'data' => 'workers[, ].civilState'
                ))
            ->add('workers.weight', Column::class, array(
                'title' => 'Workers Weight',
                'data' => 'workers[, ].weight'
                ))
            ->add('workers.height', Column::class, array(
                'title' => 'Workers Height',
                'data' => 'workers[, ].height'
                ))
            ->add('workers.eyesColor', Column::class, array(
                'title' => 'Workers EyesColor',
                'data' => 'workers[, ].eyesColor'
                ))
            ->add('workers.skinColor', Column::class, array(
                'title' => 'Workers SkinColor',
                'data' => 'workers[, ].skinColor'
                ))
            ->add('workers.hairColor', Column::class, array(
                'title' => 'Workers HairColor',
                'data' => 'workers[, ].hairColor'
                ))
            ->add('workers.pvs', Column::class, array(
                'title' => 'Workers Pvs',
                'data' => 'workers[, ].pvs'
                ))
            ->add('workers.citizenship', Column::class, array(
                'title' => 'Workers Citizenship',
                'data' => 'workers[, ].citizenship'
                ))
            ->add('workers.stateBirth', Column::class, array(
                'title' => 'Workers StateBirth',
                'data' => 'workers[, ].stateBirth'
                ))
            ->add('workers.cityBirth', Column::class, array(
                'title' => 'Workers CityBirth',
                'data' => 'workers[, ].cityBirth'
                ))
            ->add('workers.foreignCityBirth', Column::class, array(
                'title' => 'Workers ForeignCityBirth',
                'data' => 'workers[, ].foreignCityBirth'
                ))
            ->add('workers.state', Column::class, array(
                'title' => 'Workers State',
                'data' => 'workers[, ].state'
                ))
            ->add('workers.city', Column::class, array(
                'title' => 'Workers City',
                'data' => 'workers[, ].city'
                ))
            ->add('workers.district', Column::class, array(
                'title' => 'Workers District',
                'data' => 'workers[, ].district'
                ))
            ->add('workers.street', Column::class, array(
                'title' => 'Workers Street',
                'data' => 'workers[, ].street'
                ))
            ->add('workers.highway', Column::class, array(
                'title' => 'Workers Highway',
                'data' => 'workers[, ].highway'
                ))
            ->add('workers.firstBetween', Column::class, array(
                'title' => 'Workers FirstBetween',
                'data' => 'workers[, ].firstBetween'
                ))
            ->add('workers.secongBetween', Column::class, array(
                'title' => 'Workers SecongBetween',
                'data' => 'workers[, ].secongBetween'
                ))
            ->add('workers.number', Column::class, array(
                'title' => 'Workers Number',
                'data' => 'workers[, ].number'
                ))
            ->add('workers.km', Column::class, array(
                'title' => 'Workers Km',
                'data' => 'workers[, ].km'
                ))
            ->add('workers.building', Column::class, array(
                'title' => 'Workers Building',
                'data' => 'workers[, ].building'
                ))
            ->add('workers.apartment', Column::class, array(
                'title' => 'Workers Apartment',
                'data' => 'workers[, ].apartment'
                ))
            ->add('workers.cpa', Column::class, array(
                'title' => 'Workers Cpa',
                'data' => 'workers[, ].cpa'
                ))
            ->add('workers.farm', Column::class, array(
                'title' => 'Workers Farm',
                'data' => 'workers[, ].farm'
                ))
            ->add('workers.town', Column::class, array(
                'title' => 'Workers Town',
                'data' => 'workers[, ].town'
                ))
            ->add('workers.area', Column::class, array(
                'title' => 'Workers Area',
                'data' => 'workers[, ].area'
                ))
            ->add('workers.postCode', Column::class, array(
                'title' => 'Workers PostCode',
                'data' => 'workers[, ].postCode'
                ))
            ->add('workers.studentsYear', Column::class, array(
                'title' => 'Workers StudentsYear',
                'data' => 'workers[, ].studentsYear'
                ))
            ->add('workers.studentsPosition', Column::class, array(
                'title' => 'Workers StudentsPosition',
                'data' => 'workers[, ].studentsPosition'
                ))
            ->add('workers.studentsCareer', Column::class, array(
                'title' => 'Workers StudentsCareer',
                'data' => 'workers[, ].studentsCareer'
                ))
            ->add('workers.studentsState', Column::class, array(
                'title' => 'Workers StudentsState',
                'data' => 'workers[, ].studentsState'
                ))
            ->add('workers.studentsLastUpdate', Column::class, array(
                'title' => 'Workers StudentsLastUpdate',
                'data' => 'workers[, ].studentsLastUpdate'
                ))
            ->add('workers.studentsInactiveAt', Column::class, array(
                'title' => 'Workers StudentsInactiveAt',
                'data' => 'workers[, ].studentsInactiveAt'
                ))
            ->add('workers.workersOccupation', Column::class, array(
                'title' => 'Workers WorkersOccupation',
                'data' => 'workers[, ].workersOccupation'
                ))
            ->add('workers.workersSpecialty', Column::class, array(
                'title' => 'Workers WorkersSpecialty',
                'data' => 'workers[, ].workersSpecialty'
                ))
            ->add('workers.workersEduCategory', Column::class, array(
                'title' => 'Workers WorkersEduCategory',
                'data' => 'workers[, ].workersEduCategory'
                ))
            ->add('workers.workersSciGrade', Column::class, array(
                'title' => 'Workers WorkersSciGrade',
                'data' => 'workers[, ].workersSciGrade'
                ))
            ->add('workers.workersPosition', Column::class, array(
                'title' => 'Workers WorkersPosition',
                'data' => 'workers[, ].workersPosition'
                ))
            ->add('workers.workersWorkPlace', Column::class, array(
                'title' => 'Workers WorkersWorkPlace',
                'data' => 'workers[, ].workersWorkPlace'
                ))
            ->add('workers.workersAdmissionDate', Column::class, array(
                'title' => 'Workers WorkersAdmissionDate',
                'data' => 'workers[, ].workersAdmissionDate'
                ))
            ->add('workers.workersWorkPhone', Column::class, array(
                'title' => 'Workers WorkersWorkPhone',
                'data' => 'workers[, ].workersWorkPhone'
                ))
            ->add('workers.workersPay', Column::class, array(
                'title' => 'Workers WorkersPay',
                'data' => 'workers[, ].workersPay'
                ))
            ->add('workers.workersState', Column::class, array(
                'title' => 'Workers WorkersState',
                'data' => 'workers[, ].workersState'
                ))
            ->add('workers.workersLastUpdate', Column::class, array(
                'title' => 'Workers WorkersLastUpdate',
                'data' => 'workers[, ].workersLastUpdate'
                ))
            ->add('workers.workersInactiveAt', Column::class, array(
                'title' => 'Workers WorkersInactiveAt',
                'data' => 'workers[, ].workersInactiveAt'
                ))
            ->add('workers.enabled', Column::class, array(
                'title' => 'Workers Enabled',
                'data' => 'workers[, ].enabled'
                ))
            ->add('workers.locked', Column::class, array(
                'title' => 'Workers Locked',
                'data' => 'workers[, ].locked'
                ))
            ->add('workers.expired', Column::class, array(
                'title' => 'Workers Expired',
                'data' => 'workers[, ].expired'
                ))
            ->add('workers.expiredAt', Column::class, array(
                'title' => 'Workers ExpiredAt',
                'data' => 'workers[, ].expiredAt'
                ))
            ->add('workers.createdAt', Column::class, array(
                'title' => 'Workers CreatedAt',
                'data' => 'workers[, ].createdAt'
                ))
            ->add('workers.updatedAt', Column::class, array(
                'title' => 'Workers UpdatedAt',
                'data' => 'workers[, ].updatedAt'
                ))
            ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'area_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'area_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
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
        return 'DRI\UsefulBundle\Entity\Area';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'area_datatable';
    }
}
