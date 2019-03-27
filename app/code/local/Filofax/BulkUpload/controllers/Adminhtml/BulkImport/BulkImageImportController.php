<?php

class Filofax_BulkUpload_Adminhtml_BulkImport_BulkImageImportController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('filofax_bulkupload/image/import');
    }

    protected function _initAction()
    {
        $this
            ->loadLayout();

        $block = $this->getLayout()
            ->createBlock('core/text', 'filofax_bulk_image_import')
            ->setText('<h1>Filofax Bulk Image Import</h1>');

        $text = $this->getLayout()
            ->createBlock('core/text', 'filofax_bulk_image_import_directions')
            ->setText(
                '<h3>This module allows for the bulk import of images.<br />
                A specific image naming convention must be adhered to.</h3>
                <p>The convention allows for the module to search for the product by sku and add the image to the specified product.</p>
                 <p>All images must be uploaded to <pre>[mageroot]/media/import/</pre> directory prior to running the import process.</p>
                <p>All image must start with product sku to be attached to followed by a __ and the label next, then the sort_order, then select either 1 or 0 with an undersore between each value for <pre>base_image, small_image, thumbnail</pre></p>
                <p>The image naming convention is as follows:
                <pre>[sku]__[location]_[label]_[sort_order]_[base_image]_[small_image]_[thumbnail].[jpg|png|gif]</pre>
                or
                <pre>[sku]__[location]_[color].[jpg|png|gif]</pre>
                location must be set, if manually setting sort order, use an arbitrary letter or combination of letters for location.<br />
                <p>An example of an image file name is below: <br/>
                <pre>20-68208___t_Memo-Black-Red_0_1_1_1.jpg</pre>
                or
                <pre>19-65400__front_Black.jpg</pre></p>
                <p>using front, iso, inside, open, as sort order. is preferred.
                <pre>[SKU]__front_Memo Black:Red.jpg</pre>
                also the program will automatically set the configurable product images if front is set for simple product.<br />
                if you want to set the image as base image, small image, and thumbnail image for the config use \'front-1\'<br />
                <pre>19-88880__front-1_Black.jpg</pre>
                <pre>19-88880__iso_Black.jpg</pre>
                <pre>19-88880__open_Black.jpg</pre>
                <pre>19-88880__inside_Black.jpg</pre>
                will set \'front-1\' for configurable and set the others with proper sort order for simple</p>
                <p>After all images are uploaded via ftp to [mageroot]/media/import/</p>
                <p>To Run, select the button below and the importer will run, parsing all images.</p>'
            );

        
        $button = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(array(
                'id'        => 'filofax_image_import_button',
                'label'     => Mage::helper('filofax_bulkupload')->__('Run Image Import'),
                'onclick'   => 'javascript:execute(); return false;'
            ));

        $url = $this->getUrl('filofax_bulkupload/adminhtml_bulkImport_bulkImageImport/execute');
        $js = $this->getLayout()
            ->createBlock('core/text')
            ->setText('
                <script>
                        function execute(callback) {
                            var mask = document.getElementById("loading-mask");        
                            mask.style.display = "block";
                            var url = "' . $url . '";
                            var xhr = new XMLHttpRequest();
                            if (!xhr) {
                                alert("httpRequest failed");
                                console.log("httpRequest failed");
                            }
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4) {
                                    if (xhr.response) {
                                        mask.style.display = "none";
                                    }
                                }
                            }
                            xhr.open("GET", url);
                            xhr.send("execute=true");
                        }
                </script>'
            );

        $this->_addContent($block);
        $this->_addContent($text);
        $this->_addContent($button);
        $this->_addJs($js);

        return $this;
    }

    public function indexAction()
    {
        $this
            ->_initAction()
            ->renderLayout()
        ;
    }
    public function executeAction()
    {
        Mage::getModel('filofax_bulkupload/bulkImageUpload')
            ->execute();
    }
}

