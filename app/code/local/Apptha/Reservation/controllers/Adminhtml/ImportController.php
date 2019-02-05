<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action {

    public function _construct() {
        
    }
    protected function _initAction() {
		
    } 
    /**
     * Index action
     */
    public function indexAction() {

                $this->loadLayout();
                $this->_addContent($this->getLayout()->createBlock('reservation/adminhtml_import_importform'));
		$this->renderLayout();	
		
		return $this;
    }
    /**
     * Import csv action
     */
    public function importcsvAction() {

        $post_data = $this->getRequest()->isPost();
        
        
        $category = Mage::getModel('catalog/category'); 
        $tree = $category->getTreeModel(); 
        $tree->load();
        $catids = $tree->getCollection()->getAllIds(); 
       
        if ($post_data) {

            $files = $_FILES['files']['tmp_name'];
            $row = 0;

            $delimiters = ',';
            $csv = file_get_contents($files);
            $delimiter = ',';
            $enclosure = '"';
            $escape = '\\';
            $terminator = "\n";
            $csv_result = array();

            $rows = explode($terminator, trim($csv));
            $names = array_shift($rows);
            $names = str_getcsv($names, $delimiter, $enclosure, $escape);
            $names = array_map('trim', $names);
            $names = array_map('strtolower', $names);
            $nc = count($names);
            $store_id = Mage::helper('core')->getStoreId();
            foreach ($rows as $row) {
                if (trim($row)) {
                    $values = str_getcsv($row, $delimiter, $enclosure, $escape);
                    if (!$values)
                        $values = array_fill(0, $nc, null);

                    $csv_result[] = array_combine($names, $values);
                }
            }

          
            if (is_array($csv_result) && !empty($csv_result)) {
           
               $attributeSetId = Mage::getModel('catalog/product')->getResource()->getEntityType()->getDefaultAttributeSetId();
               
            foreach ($csv_result as $data) {
                if($data['imagespath']){
                    $fullpath_image = explode(';',$data['imagespath']);
                
                    $imageCount =  sizeof($fullpath_image);
                }
                $_productname = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('url_key',  $data['name']);
                
               
                $skuid = substr($data['name'],3)."_".rand(5, 100000000000);
                                
                if(count($_productname)>0){
                    $productName = $data['name'].rand(5, 100);
                }else{
                    $productName = $data['name'];
                }
                
                $facilities = implode(',', explode(';', $data['facilities']));

                $map = $data['latitude'] . $data['longitude'];

                $room_type = explode(';', $data['roomtype']);

                $room_price_per_night = explode(';', $data['price_per_night']);

                $room_special_price = explode(';', $data['special_price']);

                $room_capacity = explode(';', $data['capacity_per_room']);

                $rooms_available = explode(';', $data['rooms_available']);

                $room_price_per_extra_person = explode(';', $data['room_price_per_extra_person']);

                $room_types = array();

                for ($x = 0; $x < count($room_type); $x++) {
                    $room_types[] = array('website_id' => 0, 'room_type' => $room_type[$x], 'room_price_per_night' => $room_price_per_night[$x], 'inclusions' => '',
                        'room_capacity' => $room_capacity[$x], 'room_special_price' => $room_special_price[$x], 'room_quantity' => $rooms_available[$x],'room_price_per_extra_person' => $room_price_per_extra_person[$x], 'room_for_deal' => '',
                        'room_type_id' => '', 'use_default_value' => 'on', 'delete' => '');
                }

                $stock_data_arr = array('use_config_manage_stock' => 0, 'original_inventory_qty' => 0, 'qty' => 10, 'use_config_min_qty' => 1, 'use_config_min_sale_qty' => 1,
                    'use_config_max_sale_qty' => 1, 'is_qty_decimal' => 0, 'use_config_backorders' => 1, 'use_config_notify_stock_qty' => 1,
                    'use_config_enable_qty_increments' => 1, 'use_config_qty_increments' => 1, 'is_in_stock' => 1);

                $data = array('apptha_hotel_period_from' => $data['active_from'],
                    'apptha_hotel_period_to' => $data['active_till'],
                    'apptha_hotel_check_out' => $data['check_out_time'],
                    'apptha_hotel_check_in' => $data['check_in_time'],
                    'apptha_hotel_postal_code' => $data['postal/zip_code'],
                    'apptha_hotel_contact_no' => $data['contact_number'],
                    'apptha_hotel_website' => $data['website'],
                    'apptha_hotel_internet' => $data['internet'],
                    'apptha_hotel_parking' => $data['parking'],
                    'apptha_hotel_city' => $data['city'],
                    'apptha_hotel_map' => $map,
                    'apptha_hotel_terms_conditions' => $data['terms_conditions'],
                    'apptha_hotel_country' => $data['country'],
                    'apptha_hotel_email' => $data['email'],
                    'apptha_hotel_address' => $data['address'],
                    'enable_googlecheckout' => 1,
                    'tax_class_id' => 0,
                    'price' => '100',
                    'qty' => '2',
                    'name' => $productName,
                    'description' => strip_tags($data['description']),
                    'short_description' => strip_tags($data['short_description']),
                    'sku' => $skuid,
                    'status' => 1,
                    'visibility' => 4,
                    'use_config_gift_message_available' => 1,
                    'options_container' => 'container2',
                    'image' => 'no_selection',
                    'small_image' => 1,
                    'thumbnail' => 'no_selection',
                    'banner' => '',
                    'category_ids' => implode(',',$catids),
                    'apptha_hotel_room_type' => $room_types
                    );

                

                $product = new Mage_Catalog_Model_Product();
                // Build the product
                $product->setSku($skuid);
                $product->setAttributeSetId($attributeSetId);
                $product->setTypeId('hotel');
                $product->setName($data['name']);
                
                $product->setCategoryIds(implode(',',$catids)); # some cat id's, my is 7
                $product->setWebsiteIDs(array(Mage::app()->getStore(true)->getWebsite()->getId())); # Website id, my is 1 (default frontend)
                $product->setDescription($data['description']);
                $product->setShortDescription($data['short_description']);
                 # Set some price
                
                # Custom created and assigned attributes
                $product->setAppthaHotelPeriodFrom($data['apptha_hotel_period_from']);

                $product->setAppthaHotelPeriodTo($data['apptha_hotel_period_to']);

                $product->setAppthaHotelCheckOut($data['apptha_hotel_check_out']);

                $product->setAppthaHotelCheckIn($data['apptha_hotel_check_in']);

                $product->setAppthaHotelPostalCode($data['apptha_hotel_postal_code']);

                $product->setAppthaHotelContactNo($data['apptha_hotel_contact_no']);

                $product->setAppthaHotelWebsite($data['apptha_hotel_website']);

                $product->setAppthaHotelInternet($data['apptha_hotel_internet']);

                $product->setAppthaHotelParking($data['apptha_hotel_parking']);

                $product->setAppthaHotelCity($data['apptha_hotel_city']);

                $product->setAppthaHotelMap($data['apptha_hotel_map']);

                $product->setAppthaHotelTermsConditions($data['apptha_hotel_terms_conditions']);

                $product->setAppthaHotelCountry(trim($data['apptha_hotel_country']));

                $product->setAppthaHotelEmail($data['apptha_hotel_email']);

                $product->setAppthaHotelAddress($data['apptha_hotel_address']);

                //Default Magento attribute
                $product->setWeight(4.0000);
                $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                $product->setStatus(1);
                $product->setTaxClassId(0); # My default tax class
                
               
                if($imageCount>0){
                    
                    for($i=0;$i<$imageCount;$i++){
                        $imagename = "media/hotelreservation/images/".$fullpath_image[$i];
                        if(file_exists($imagename)){
                            $product->addImageToMediaGallery($imagename, array('image', 'small_image', 'thumbnail', 'banner'), false, false); 
                        }
                        
                        
                } }
                
                $product->setCreatedAt(strtotime('now'));
                $product->setUpdatedAt(strtotime('now'));
                
                
                try {
                   
                    $product->save();
                   
                    $product_id = $product->getId();
                    for ($i = 0; $i < count($data['apptha_hotel_room_type']); $i++) {
                        $roomModel = Mage::getModel('reservation/roomtypes');
                        $roomModel->setStoreId(0)
                                ->setEntityId($product_id)
                                ->setRoomType($data['apptha_hotel_room_type'][$i]['room_type'])
                                ->setRoomQuantity($data['apptha_hotel_room_type'][$i]['room_quantity'])
                                ->setRoomCapacity($data['apptha_hotel_room_type'][$i]['room_capacity'])
                                ->setRoomPricePerNight($data['apptha_hotel_room_type'][$i]['room_price_per_night'])
                                ->setRoomSpecialPrice($data['apptha_hotel_room_type'][$i]['room_special_price'])
                                ->setRoomPricePerExtraPerson($data['apptha_hotel_room_type'][$i]['room_price_per_extra_person'])
                                ->setRoomForDeal('')
                                ->setroomPricePerPerson($data['apptha_hotel_room_type'][$i]['room_price_per_extra_person'])
                                ->save();
                    }
                    
                 $ex = 1 ;   
                    
                   
                } catch (Exception $ex) { //Handles the error
                    
                    
                    $ex = 0 ;
                }

               
            } /* Finishes the for each loop  */
           
                if($ex == 1){ 
                    $message = $this->__('CSV Successfully Imported');
                    Mage::getSingleton('adminhtml/session')->addSuccess($message);
                    $this->_redirect('reservation/adminhtml_import');
                }else { 
                    $message = $this->__('There was an error while importing');
                    Mage::getSingleton('adminhtml/session')->addError($message);
                    $this->_redirect('reservation/adminhtml_import');
                } 
            
            
            
            } else {  /* end If for checking array is empty and it is an array  */
                  $message = $this->__('There was an error while importing');
                  Mage::getSingleton('adminhtml/session')->addError($message);
                  $this->_redirect('reservation/adminhtml_import');
            }
        

        }
    }

    /**
     * Export csv load template file action
     */
    
    public function exportformhtmlAction() {

        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('reservation/adminhtml_import_export'));
	$this->renderLayout();	
	return $this;
      
    

    }
    
    public function formatHtml($str){
        if($str != ''){
            $string = strip_tags($str);
              $output = str_replace(array("\r\n", "\r"), "\n", $string);
                $lines = explode("\n", $output);
                $new_lines = array();

                foreach ($lines as $i => $line) {
                    if(!empty($line))
                        $new_lines[] = trim($line);
                }
                $description = implode($new_lines);
               return $description;
        }
    }
    /**
     * Export csv action
     */
    public function exportcsvAction() {


        $post_data = $this->getRequest()->isPost();
        if ($post_data) {

            $collection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSelect('*');
           

         

            foreach ($collection as $product) {

               $entity_id = $product->getId();

                $roomCollection = Mage::getModel('reservation/roomtypes')->getCollection()
        					->addEntityIdFIlter($entity_id);
                $room_type = '';
                $room_price_per_person = '';
                $room_quantity = '';
                $room_price_per_extra_person = '';
                $room_special_price = '';
                $room_capacity = '';
                $room_price_per_night = '';

                foreach($roomCollection as $room){
                    
                    $room_type .= $room->getRoomType().";";
                    $room_price_per_person .= $room->getRoomPricePerNight().";";
                    $room_quantity .= $room->getRoomQuantity().";";
                    $room_price_per_extra_person .= $room->getRoomPricePerExtraPerson().";";
                    $room_special_price .= $room->getRoomQuantity().";";
                    $room_capacity .= $room->getRoomQuantity().";";
                    $room_price_per_night .= $room->getRoomPricePerNight().";";
                    
                }
                
             $hotelFacilities = $this->formatHtml($product->getAppthaHotelFacilites());
                
              $hotelAddress = str_replace(',',' ',$product->getAppthaHotelAddress());   
                 
              $hotelAddress1 = $this->formatHtml($hotelAddress);
              
              $description = $this->formatHtml($product->getDescription());
              
              $sdescription = $this->formatHtml($product->getShortDescription()); 
              
              $hotelTermsConditions = $product->getAppthaHotelTermsConditions();
              
              $hotelSlogan = $product->getAppthaHotelslogan();
              
              if($product->getAppthaHotelPeriodFrom() != ''){   
              
                  $products[] = array('Active_from'=>trim($product->getAppthaHotelPeriodFrom()),
                             'Active_till'=>trim($product->getAppthaHotelPeriodTo()),
                             'Check_Out_Time'=>trim($product->getAppthaHotelCheckOut()),
                             'Check_In_Time'=>trim($product->getAppthaHotelCheckIn()),
                             'Postal/Zip_code'=>trim($product->getAppthaHotelPostalCode()),
                             'Contact_number'=>trim($product->getAppthaHotelContactNo()),
                             'Fax'=>trim($product->getAppthaHotelFaxNo()),
                             'Slogan'=>trim($hotelSlogan),
                             'Website'=>trim($product->getAppthaHotelWebsite()),
                             'Facilities'=>trim($hotelFacilities),
                             'Internet'=>trim($product->getAppthaHotelInternet()),
                             'Parking'=>trim($product->getAppthaHotelParking()),
                             'City'=>trim($product->getAppthaHotelCity()),
                             'Latitude'=>'12',
                             'Longitude'=>'21',
                             'Terms_Conditions'=>trim($hotelTermsConditions),
                             'Country'=>trim($product->getAppthaHotelCountry()),
                             'Email'=>trim($product->getAppthaHotelEmail()),
                             'Address'=>trim($hotelAddress1),
                             'Name'=>trim($product->getName()),
                             'Description'=>trim($description),
                             'Short_Description'=>trim($sdescription),
                             'Roomtype'=>rtrim($room_type, ";"),
                             'Price_per_night'=>rtrim($room_price_per_night, ";"),
                             'Special_Price'=>rtrim($room_special_price, ";"),
                             'Capacity_per_room'=>rtrim($room_capacity, ";"),
                             'Rooms_available'=>rtrim($room_quantity, ";"),
                             'room_price_per_extra_person'=>rtrim($room_price_per_extra_person, ";"));
              }

            }


$csv_data =  Mage::helper('reservation/data')->array_to_scv($products);

$fileName = 'export.csv';


        $contentType = 'application/octet-stream';
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($csv_data));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($csv_data);
        $response->sendResponse();
        
        die;



        }
       

    }


}
?>