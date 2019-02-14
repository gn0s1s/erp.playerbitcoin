<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "auth";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */

$route['shoppingcart'] = 'ov/compras/carrito';
$route['show_todos_categoria'] = 'ov/compras/show_todos_categoria';
$route['add_carrito'] = 'ov/compras/add_carrito';
$route['add_merc'] = 'ov/compras/add_merc';
$route['printContentCartButton'] = 'ov/compras/printContentCartButton';
$route['ver_carrito'] = 'ov/compras/ver_carrito';
$route['DatosEnvio'] = 'ov/compras/DatosEnvio';
$route['buy'] = 'ov/compras/comprar';
$route['consultarBlockchain'] = 'ov/compras/consultarBlockchain';
$route['pagarVentaBlockchain'] = 'ov/compras/pagarVentaBlockchain';
$route['show_todos_tipo_mercancia'] = 'ov/compras/show_todos_tipo_mercancia';
$route['ov/networkProfile'] = 'ov/perfil_red';
$route['ov/networkProfile/profile'] = 'ov/perfil_red/perfil';
$route['ov/networkProfile/photo'] = 'ov/perfil_red/foto';
// $route['ov/networkProfile/newAffiliate/(:num)'] = 'ov/perfil_red/nuevo_afilido';
$route['ov/accountStatus'] = 'ov/billetera2/index_estado';
$route['ov/accountStatus/status'] = 'ov/billetera2/estado';
$route['ov/accountStatus/accountHistory'] = 'ov/billetera2/historial_cuenta';
$route['ov/wallet'] = 'ov/billetera2/index';
$route['ov/wallet/requestPayment'] = 'ov/billetera2/pedir_pago';
$route['ov/wallet/history'] = 'ov/billetera2/historial';
$route['ov/reports'] = 'ov/compras/reportes';
$route['ov/surveys'] = 'ov/cgeneral/encuestas';
$route['ov/archive'] = 'ov/cabecera/archivo';
$route['ov/board'] = 'ov/cabecera/tablero';
$route['ov/share'] = 'ov/cabecera/compartir';
$route['ov/email'] = 'ov/cabecera/email';
$route['ov/information'] = 'ov/escuela_negocios/informacion';
$route['ov/presentations'] = 'ov/escuela_negocios/presentaciones';
$route['ov/ebooks'] = 'ov/escuela_negocios/ebooks';
$route['ov/downloads'] = 'ov/escuela_negocios/descargas';
$route['ov/events'] = 'ov/escuela_negocios/eventos';
$route['ov/news'] = 'ov/escuela_negocios/noticias';
$route['ov/videos'] = 'ov/escuela_negocios/videos';
$route['ov/statistics'] = 'ov/compras/estadistica';
$route['ov/personalWeb'] = 'ov/cgeneral/web_personal';
$route['ov/chat'] = 'ov/cgeneral/chat';
$route['ov/coupons'] = 'ov/escuela_negocios/bonos';
$route['ov/acknowledgments'] = 'ov/escuela_negocios/reconocimientos';
$route['ov/suggestion'] = 'ov/cabecera/sugerencia';

