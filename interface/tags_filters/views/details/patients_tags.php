<div id="patient-details-container" style="display: none">
    <?php echo $this->navbar; ?>

    <table class="display" cellspacing="0" width="100%" id="exampleTable">
        <thead>
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