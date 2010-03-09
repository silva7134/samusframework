<?php
/**
 * Description of TreeView
 *
 * @author samus
 */ 
class TreeView {

    private $a_objElements = array ();
    private $iTableID;
    private static $iInstances = 0;

    /**
     * Constructor
     *
     */
    public function __construct() {
        self::$iInstances ++;
        $this->iTableID = self::$iInstances;
    }

    /**
     * Add a document object to the Treeview
     *
     * @param string $sName The name of the document object
     * @param string $sAction a javascript action that will get executed on "onclick" event handler
     */
    public function addDocument ($sName, $sAction='') {
        $this->a_objElements [] = new CLASS_Treeview_Document($sName, $sAction);
    }

    /**
     * Add a folder object to the Treeview
     *
     * @param string $sName The name of the folder object
     * @param bool $bExpanded Set to true if you want the folder to be expanded by default
     * @return integer The index in the array of elements... This is useful for retrieving the object using getObjFolder
     */
    public function addFolder ($sName, $bExpanded = false) {
        $iElements = count ($this->a_objElements);
        $this->a_objElements [] = new CLASS_Treeview_Folder($this->iTableID.'.'.($iElements + 1), $sName, $bExpanded);
        return $iElements;
    }

    /**
     * Return a folder object in the array of elements
     *
     * @param integer $iKey The index in the array of the CLASS_Treeview_Folder object to retrieve
     * @return CLASS_Treeview_Folder
     */
    public function getObjFolder ($iKey) {
        if ($this->a_objElements[$iKey] instanceof CLASS_Treeview_Folder) {
            return $this->a_objElements[$iKey];
        }
        else {
            throw new Exception('Invalid object requested');
        }
    }

    /**
     * Render a DIV of the treeview object
     *
     * @param integer $iWidth The width in pixel of the div. If you leave this param to 0, the width will be 100% of the container's width. Scrollbars will appear on overflow
     * @param integer $iHeight The height in pixel of the div. If you leave this param to 0, the height will be 100% of the container's height. Scrollbars will appear on overflow
     */
    public function render ($iWidth = 0, $iHeight = 0) {

        $sCollapse = 'Collapse all';
        $sExpand = 'Expand all';


        $sReturn = '
                <div style="width: '.($iWidth == 0 ? '100%':" $iWidth px;").'; height: '.($iHeight == 0 ? '100%':" $iHeight px;").'; overflow-x:auto; overflow-y:auto; text-align: left; padding: 5px;">
                    <a href="#" onclick="treeviewExpandAll ('.$this->iTableID.')" class="lnktxt">'.$sExpand.'</a> <a href="#" onclick="treeviewCollapseAll ('.$this->iTableID.')" class="lnktxt">'.$sCollapse.'</a>
                    <ul id="objTree'.$this->iTableID.'" class="treeview">
            ';
        foreach ($this->a_objElements as $objElement) {
            $sReturn .= $objElement->render ();
        }
        $sReturn .= '
                    </ul>
                </div>
            ';

        return $sReturn;
    }
}

/**
 * This is a subclass of the CLASS_Treeview to deal with document objects
 *
 */
class CLASS_Treeview_Document {
    private $sName;
    private $sAction;

    /**
     * The constructor
     *
     * @param string $sName The name of the document object
     * @param string $sAction a javascript action that will get executed on "onclick" event handler
     */
    public function __construct($sName, $sAction) {
        $this->sName = $sName;
        $this->sAction = $sAction;
    }

    /**
     * Will render the Document element in the treeview
     *
     */
    public function render () {
        global $objSession;
        return '
                <li class="treeviewFolderLi" style="margin-left: 18px;"><img src="images/document.gif">'.($this->sAction != '' ? '<a href="#" id="node_114" class="lnktxt" onclick="'.$this->sAction.'">'.$this->sName.'</a>':$this->sName).'</li>
            ';
    }
}

/**
 * This is a subclass of the CLASS_Treeview to deal with folder objects
 *
 */
class CLASS_Treeview_Folder {
    private $sName;
    private $bExpanded;
    private $sIDPrefix;
    private $a_objElements = array ();

    /**
     * The constructor
     *
     * @param string $sIDPrefix The unique ID of this floder
     * @param string $sName The name of the folder object
     * @param bool $bExpanded Set to true if you want the folder to be expanded by default
     */
    public function __construct($sIDPrefix, $sName, $bExpanded) {
        $this->sName = $sName;
        $this->bExpanded = $bExpanded;
        $this->sIDPrefix = $sIDPrefix;
    }

    /**
     * Add a document object to the specific folder in which we are
     *
     * @param string $sName The name of the document object
     * @param string $sAction a javascript action that will get executed on "onclick" event handler
     */
    public function addDocument ($sName, $sAction='') {
        $this->a_objElements [] = new CLASS_Treeview_Document($sName, $sAction);
    }

    /**
     * Add a folder object to the specific folder in which we are
     *
     * @param string $sName The name of the folder object
     * @param bool $bExpanded Set to true if you want the folder to be expanded by default
     * @return integer The index in the array of elements... This is useful for retrieving the object using getObjFolder
     */
    public function addFolder ($sName, $bExpanded = false) {
        $iElements = count ($this->a_objElements);
        $this->a_objElements [] = new CLASS_Treeview_Folder($this->sIDPrefix.'.'.($iElements + 1), $sName, $bExpanded);
        return $iElements;
    }

    /**
     * Return a folder object in the array of elements
     *
     * @param integer $iKey The index in the array of the CLASS_Treeview_Folder object to retrieve
     * @return CLASS_Treeview_Folder
     */
    public function getObjFolder ($iKey) {
        if ($this->a_objElements[$iKey] instanceof CLASS_Treeview_Folder) {
            return $this->a_objElements[$iKey];
        }
        else {
            throw new Exception('Invalid object requested');
        }
    }

    /**
     * Will render the folder element in the treeview
     *
     */
    public function render () {
        global $objSession;
        $sReturn = '
                <li class="treeviewFolderLi"><img id="objTreeCollapser'.$this->sIDPrefix.'" src="images/'.($this->bExpanded ? 'collapser':'expander').'.gif" style="visibility:'.(count ($this->a_objElements) > 0 ? 'show':'hidden').'"  onclick="treeviewExpandCollapse(\''.$this->sIDPrefix.'\')"><img src="images/folder.gif" ondblclick="treeviewExpandCollapse(\''.$this->sIDPrefix.'\')"><span ondblclick="treeviewExpandCollapse(\''.$this->sIDPrefix.'\')" onselectstart="return false;">'.$this->sName.'</span>
                    <ul id="objTreeUL'.$this->sIDPrefix.'" class="treeviewFolderUl" style="'.($this->bExpanded ? 'display: block':'display: none').'">
            ';
        foreach ($this->a_objElements as $objElement) {
            $sReturn .= $objElement->render ();
        }
        $sReturn .= '
                    </ul>
                </li>
            ';

        return $sReturn;
    }
}
?>
