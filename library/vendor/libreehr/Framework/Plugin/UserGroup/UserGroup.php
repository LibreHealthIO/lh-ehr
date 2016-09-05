<?php
namespace Framework\Plugin\UserGroup;

class UserGroup
{
    protected $fields = array();
    protected $id = null;
    
    public function __construct( $id )
    {
        $this->id = $id;
    }
    
    public function add( UserGroupFieldIF $field )
    {
        $this->fields[]= $field;    
    }
    
    public function save()
    {
        if ( count( $this->fields ) ) {
            $binds = array();
            $statement = "UPDATE users SET ";
            $count = 0;
            foreach ( $this->fields as $field ) {
                $statement .= "{$field->getName()} = ? ";
                if ( $count < count( $this->fields ) - 1 ) {
                    $statement .= ", ";
                }
                $binds[]= $field->getValue();
                $count++;
            }
            $statement .= "WHERE id = ?";
            $binds[]= $this->id;
            sqlStatement( $statement, $binds );
        }
    }

    /*
     * Save the fields as user_settings, not in users table
     */
    public function saveAsUserSettings()
    {
        if ( count( $this->fields ) ) {
            foreach ( $this->fields as $field ) {
                $ds = "DELETE FROM user_settings WHERE setting_user = ? AND setting_label = ?";
                sqlStatement( $ds, [ $this->id, $field->getName() ] );
                $statement = "INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES ( ?, ?, ? ) ";
                sqlStatement( $statement, [ $this->id, $field->getName(), $field->getValue() ] );
            }
        }
    }
    
    public function renderUserAdminAdd()
    {
        $count = 0;
        foreach ( $this->fields as $field ) {
            
            if ( $count % 2 === 0 ) {
                // Create a new row
                echo '<tr>';
            }
            
            // print the label column
            echo "<td style='width:150px;'><span class='text'>{$field->getLabel()}</span></td>";
            
            // print a column
            echo '<td>';
            
            // print the field
            $field->renderUserAdminAdd();
            
            echo '</td>';
            
            if ( $count % 2 !== 0 ) {
                // Close the row
                echo '</tr>';
            }
            
            $count++;
        }
    }    
}
