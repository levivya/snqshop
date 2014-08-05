<?php


class FME_Gmapstrlocator_Block_Links extends Mage_Page_Block_Template_Links_Block
{
    
    protected function _toHtml()
    {
        $html ='';
        
        if(Mage::getStoreConfig('gmapstrlocator/manage_links/header_enable')){
            
            $html = '<li class="top-link-gmapstore" id="mouse_header_store_search_container">';
                
                $html.= '<a title="'.$this->helper('gmapstrlocator')->getHeaderLinkLabel().'" href="'.$this->helper('gmapstrlocator')->getStoreLocatorPageUrl().'">'.$this->helper('gmapstrlocator')->getHeaderLinkLabel().'</a>';
                
                $html.= '<div class="top-link-gmapstore-content" id="mouse_header_store_search_box" style="display:none">
                
                            <label>'.$this->helper('gmapstrlocator')->__('Search by entering a ZIP code, or your city and state').'</label>
                            <form name="gmap-search-form" action="'.$this->helper('gmapstrlocator')->getStoreLocatorPageUrl().'" method="POST" >
                                <input type ="text" name="gmap-search-address" id="gmap-search-field" />
                                <button class="button" type="submit"><span><span>'.$this->helper('gmapstrlocator')->__('Find a store').'</span></span></button>
                            </form>                                
                        </div>';
                
            $html.= '</li>';
            
            
            $html.= '<script type="text/javascript">';
                        
                        $html.= 'if($("mouse_header_store_search_container")){';
                                
                            $html.= 'Event.observe("mouse_header_store_search_container", "mouseover", function(event) {';
                            
                                    $html.= '$("mouse_header_store_search_box").show();';
                                       
                            $html.= '});';
                            
                            $html.= 'Event.observe("mouse_header_store_search_container", "mouseleave", function(event) {';
                            
                                    $html.= '$("mouse_header_store_search_box").hide();';
                                       
                            $html.= '});';  
                        
                        $html.= '}';
                        
                        
                        
                                  
                    
            $html.= '</script>';
            
            return $html;
            
        }
        
        return $html;
        
    }
    
}
