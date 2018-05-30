<?php
if (!empty($result)) {
?>
    <ul id="country-list">
    <?php
        foreach ($result as $location) {
        ?>
            <li onClick="selectCountry('<?php echo $location["description"]; ?>','<?php echo $location["place_id"]; ?>');"><?php echo $location["description"]; ?></li>
        <?php } ?>
    </ul>
<?php } ?>