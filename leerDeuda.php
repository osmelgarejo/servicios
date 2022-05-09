
<?php
 
$idDeuda = '13';
$apiUrl = 'https://staging.adamspay.com/api/v1/debts/' . $idDeuda;
$apiKey = 'ap-5eaeae7e8efc2f58701f0d51';
 
$curl = curl_init();
 
curl_setopt_array($curl,[
 CURLOPT_URL => $apiUrl,
 CURLOPT_HTTPHEADER => ['apikey: '.$apiKey],
 CURLOPT_RETURNTRANSFER => true
 ]);
 
$response = curl_exec($curl);
if( $response ){
  $data = json_decode($response,true);
 
  // Verificar estado de pago
  $debt = isset($data['debt']) ? $data['debt'] : null;
  if( $debt ){
    $payUrl = $debt['payUrl'];
    $label = $debt['label'];
    $objStatus = $debt['objStatus']['status'];
    $payStatus = $debt['payStatus']['status'];
    $isActive = false !== array_search($objStatus,['active','alert','success']);
    $isPaid =$payStatus === 'paid';
 
    echo "Deuda encontrada, URL=$payUrl\n";
    echo "Concepto: $label\n";
    echo "Activa: ",($isActive?'Si':'No'),"\n";
    echo "Pagada: ";
    if( $isPaid ){
      $payTime  = $debt['payStatus']['time'];
      echo "Si, en fecha $payTime\n";
    }
    else {
      echo "No\n";
    }
 
  } else {
    echo "No se pudo obtener datos de la deuda\n";
    print_r($data['meta']);
  }
 
}
else {
  echo 'curl_error: ',curl_error($curl);
}
curl_close($curl);
