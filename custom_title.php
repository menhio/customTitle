<?php
//[node:field-tripulante-salud] Análisis: [node:field-fecha-analisis-salud:custom:d-m-Y]

$nid = $node->nid;
$query = db_select('node', 'n');
$query->leftJoin('field_data_field_fecha_analisis_salud', 'f', 'f.entity_id = n.nid');
$query->leftJoin('field_data_field_tripulante_salud', 's', 's.entity_id = n.nid');

$query->leftJoin('node', 'n2', 'n2.nid = s.field_tripulante_salud_target_id');
$query->leftJoin('field_data_field_nombre_personal', 'nom', 'nom.entity_id = n2.nid');
$query->leftJoin('field_data_field_ap_paterno_personal', 'pat', 'pat.entity_id = n2.nid');
$query->leftJoin('field_data_field_ap_materno_personal', 'mat', 'mat.entity_id = n2.nid');

$query->addField('nom', 'field_nombre_personal_value', 'nombre');
$query->addField('pat', 'field_ap_paterno_personal_value', 'paterno');
$query->addField('mat', 'field_ap_materno_personal_value', 'materno');
$query->addField('f', 'field_fecha_analisis_salud_value', 'fecha');

 
$query->condition('n.type', 'salud', '=');
$query->condition('n.nid', $nid, '=');
$exeResults = $query->execute();
$results = $exeResults->fetchAll();

foreach ($results as $result) {
  $nombre = isset($result->nombre) ? $result->nombre : '';
  $paterno = isset($result->paterno) ? $result->paterno : '';
  $materno = isset($result->materno) ? $result->materno : '';
  
  if (isset($result->fecha)) {
    $raw_fecha = new DateTime($result->fecha);
    $fecha = $raw_fecha->format('d-m-Y');
    $title = $nombre . ' ' . $paterno . ' ' . $materno . ' - ' . 'Análisis: ' . $fecha;
  } else {
    $title = $nombre . ' ' . $paterno . ' ' . $materno;
  }
}

return $title;

?>
