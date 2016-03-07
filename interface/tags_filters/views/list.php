<?php include( $this->modal ); ?>

<div id="log-container">

    <?php include( $this->navbar ); ?>

    <table class="display formtable" cellspacing="0" width="100%" id="<?php echo $this->dataTable->getTableId() ?>">
        <thead>
        <tr>
            <?php foreach ( $this->dataTable->getColumns() as $column ) { ?>
                <td class="column-search-filter" align="center" rowspan="1" colspan="1">
                    <?php if ( $column->isSearchable() ) { ?>
                        <?php if ( is_a( $column->getBehavior(), 'ActiveElement' ) &&
                            is_array( $column->getBehavior()->getMap() ) ) { ?>
                            <select class="search_init">
                                <option value=""> -- </option>
                                <?php foreach( $column->getBehavior()->getMap() as $value => $label ) { ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php } ?>
                            </select>
                        <?php } else { ?>
                            <input class="search_init" type="text" value="">
                        <?php } ?>
                    <?php } else { ?>
                        &nbsp;
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach ( $this->dataTable->getColumns() as $column ) { ?>
                <th style="width: <?php $column->getWidth(); ?>"><?php echo $column->getTitle(); ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>