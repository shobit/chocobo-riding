<?php

class ORM extends ORM_Core 
{
    
    // FUNC: Ajoute la gestion des champs created et updated à la méthode save()
    public function save() 
    {
    	if ($this->id == 0 and isset($this->created))
    		$this->created = time();
    	if ($this->id > 0 and isset($this->updated))
    		$this->updated = time();
    	parent::save();
    }

	public function open_paren() 
	{
		$this->db->open_paren();
		return $this;	
	}
	
	public function close_paren() 
	{
		$this->db->close_paren();
		return $this;
	}
  
    /**
     * Transaction status
     *
     * @var in_tx
     */
    protected $in_tx = false;

	public function __construct($id = NULL){
	    parent::__construct($id);
	}

 	public function __destruct() {
        self::rollback_tx();
    }

    /**
     * Start the transaction
     *
     * @return  void
     */
 	public function start_tx() {
	  if ($this->in_tx === false) {
            $this->db->query('SET AUTOCOMMIT=0');
            $this->db->query('BEGIN');
          }
          $this->in_tx = true;
 	}

    /**
     * End the transaction
     *
     * @return  void
     */
 	public function end_tx() {
	  if ($this->in_tx === true) {
            $this->db->query('COMMIT');
            $this->db->query('SET AUTOCOMMIT=1');
          }
          $this->in_tx = false;
 	}

    /**
     * Rollback the transaction
     *
     * @return  void
     */
    public function rollback_tx() {
        if ($this->in_tx === true) {
          $this->db->query('ROLLBACK');
          $this->db->query('SET AUTOCOMMIT=1');
        }
        $this->in_tx = false;
    }
    
}