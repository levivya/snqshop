<?php

class FME_Gmapstrlocator_Block_Adminhtml_Attributes_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('attributesGrid');
      $this->setDefaultSort('attributes_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('gmapstrlocator/attributes')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('attributes_id', array(
          'header'    => Mage::helper('gmapstrlocator')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'attributes_id',
      ));

      $this->addColumn('attributes_name', array(
          'header'    => Mage::helper('gmapstrlocator')->__('Attribute Name'),
          'align'     =>'left',
          'index'     => 'attributes_name',
	  'width'     => '350px',
      ));
      
    

      $this->addColumn('attributes_status', array(
          'header'    => Mage::helper('gmapstrlocator')->__('Attribute Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'attributes_status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('gmapstrlocator')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('gmapstrlocator')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('gmapstrlocator')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('gmapstrlocator')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('attributes_id');
        $this->getMassactionBlock()->setFormFieldName('attributes');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('gmapstrlocator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('gmapstrlocator')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('gmapstrlocator/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('gmapstrlocator')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('gmapstrlocator')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}