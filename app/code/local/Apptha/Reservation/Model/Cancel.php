<?php


class Apptha_Reservation_Model_Cancel extends Mage_Core_Model_Abstract {

public function cancelOrder($orderid,$orderincreid) {

        /* Sender Email */
         $merchantMailId   = Mage::getStoreConfig('trans_email/ident_general/email');
        /* Sender Name */
         $merchantName     = Mage::getStoreConfig('trans_email/ident_general/name'); 
         $templeId       =  (int)Mage::getStoreConfig('reservation/reservation_custom_email/cancelorder_custom_template');
        
       //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                            ->loadDefault('reservation_reservation_custom_email_cancelorder_custom_template');
        }
        
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        
        $cname = $customer->getName();//Property Email Owner
        $emailTemplate->setSenderName($cname);     //mail sender name
        $emailTemplate->setSenderEmail( $customer->getEmail());  //mail sender email id

        $emailTemplateVariables = (array('ownername'=> $merchantName,'orderid' =>$orderid,'orderincreid'=>$orderincreid,'cusname'=>$cname));

        $emailTemplate->setDesignConfig(array('area' => 'frontend'));

        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
        
        $emailTemplate->send($merchantMailId, $cname, $emailTemplateVariables);  //send mail to customer email ids


    }

}

?>