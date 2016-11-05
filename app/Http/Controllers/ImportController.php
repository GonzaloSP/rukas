<?php

/**
 * Laravel - A PHP Framework For Web Artisans.
 *
 * @author   Gonzalo Perilhou <perilhou@gmail.com>
 */
namespace App\Http\Controllers;

use Mail;

class ImportController extends Controller
{
    public function getIndex()
    {
        $data['emailTo'] = 'email@to.com';
        $data['from'] = 'admin@localhost.com';
        $files = scandir('CSV');
        ini_set('max_execution_time', 0);
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                $filepart = explode('_', $filename);
                $IDGP = $filepart[1];
                $IDEN = $filepart[2];
                $IDDV = $filepart[3];
                $ID_Empresa = $filepart[4];
                $GRENDI = $IDGP.$IDEN.$IDDV.$ID_Empresa;
                $file = fopen('CSV/'.$filename, 'r');
                $count = 0;
                switch ($filepart[0]) {
                      case 'BDPD':
                          while (($emapData = fgetcsv($file, ',')) !== false) {
                              if ($count != 0) {
                                  $CDEM = $IDGP.$IDEN.$IDDV;
                                  $model = new \App\Bdpb();
                                  $model->CDEM = $CDEM;
                                  $model->IDGP = $IDGP;
                                  $model->IDEN = $IDEN;
                                  $model->IDDV = $IDDV;
                                  $model->TPPD = $emapData[0];
                                  $model->ORVT = $emapData[1];
                                  $model->OFVT = $emapData[2];
                                  $VEVT = $emapData[3];
                                  if ($VEVT == '') {
                                      $VEVT = 0;
                                  }
                                  $vmodel = new \App\Vended();
                                  $vmodel->setConnection('mysql2');
                                  $isData = $vmodel::where('ID_Empresa', $ID_Empresa)->where('ID_Grendi', $GRENDI)->where('Codigo', $VEVT)->get();
                                  if (count($isData) == 0) {
                                      $lisemp = new \App\Lisemp();
                                      $lisemp->setConnection('mysql2');
                                      $is_count = $lisemp::where('ID_Empresa', $ID_Empresa)->get();
                                      if (count($is_count) == 0) {
                                          $lisemp = new \App\Lisemp();
                                          $lisemp->setConnection('mysql2');
                                          $lisemp->ID_Empresa = $ID_Empresa;
                                          $lisemp->save();
                                      }

                                      $grendi = new \App\Grendi();
                                      $grendi->setConnection('mysql2');
                                      $is_count = $grendi::where('ID_Grendi', $GRENDI)->get();
                                      if (count($is_count) == 0) {
                                          $grendi = new \App\Grendi();
                                          $grendi->setConnection('mysql2');
                                          $grendi->ID_Grendi = $GRENDI;
                                          $grendi->ID_Empresa = $ID_Empresa;
                                          $grendi->save();
                                      }
                                      $vmodel = new \App\Vended();
                                      $vmodel->setConnection('mysql2');
                                      $vmodel->ID_Empresa = $ID_Empresa;
                                      $vmodel->ID_Grendi = $GRENDI;
                                      $vmodel->Codigo = $VEVT;
                                      $vmodel->save();
                                      $model->VEVT = $vmodel->ID_Vendedor;
                                      $emaildata['mensaje'] = 'Se ha creado el vendedor con el ID '.$vmodel->ID_Vendedor;
                                      Mail::send('emails.adminNotification', $emaildata, function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Vendedor!');
                                      });
                                  } else {
                                      $model->VEVT = $isData[0]->ID_Vendedor;
                                  }

                                  $model->NRPD = $emapData[4];
                                  $fdate = \App\Bdit::convertDate($emapData[5]);
                                  $model->FEPD = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[6]);
                                  $model->HRPD = $fdate;
                                  $model->CPPD = $emapData[7];
                                  $model->OCPD = $emapData[8];
                                  $fdate = \App\Bdit::convertDate($emapData[9]);
                                  $model->FCOC = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[10]);
                                  $model->FVOC = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[11]);
                                  $model->FPEN = $fdate;
                                  $model->STPD = $emapData[12];
                                  $model->DSST = $emapData[13];
                                  $model->DRST = $emapData[14];
                                  $model->CIST = $emapData[15];
                                  $model->CPST = $emapData[16];
                                  $model->TEST = $emapData[17];
                                  $model->PAST = $emapData[18];
                                  $model->DSPY = $emapData[20];
                                  $model->BTPD = $emapData[21];
                                  $model->DSBT = $emapData[22];
                                  $CUBT = $emapData[23];

                                  $soldto = new \App\Soldto();
                                  $soldto->setConnection('mysql2');
                                  $isData = $soldto::where('Cuit', $CUBT)->get();

                                  if (count($isData) == 0) {
                                      $vmodel = new \App\Soldto();
                                      $vmodel->setConnection('mysql2');
                                      $vmodel->Cuit = $CUBT;
                                      $vmodel->RazonSocial = $emapData[13];
                                      $vmodel->Direccion = $emapData[14];
                                      $vmodel->Ciudad = $emapData[15];
                                      $vmodel->Cp = $emapData[16];
                                      $vmodel->Telefono = $emapData[17];
                                      $vmodel->save();
                                      $model->CUBT = $vmodel->ID_SoldTo;
                                      $mensaje = 'Se ha creado un nuevo registro en SOLDTO con el ID '.$vmodel->ID_SoldTo;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  } else {
                                      $model->CUBT = $isData[0]->ID_SoldTo;
                                  }
                                  $model->SPPD = $emapData[24];
                                  $model->DSSP = $emapData[25];
                                  $model->DRSP = $emapData[26];
                                  $model->CISP = $emapData[27];
                                  $model->CPSP = $emapData[28];
                                  $model->TESP = $emapData[29];
                                  $model->PASP = $emapData[30];
                                  $model->INSP = $emapData[31];
                                  $model->RGSP = $emapData[32];
                                  $ICPD = $emapData[34];
                                  $incoterm = new \App\Incote();
                                  $incoterm->setConnection('mysql2');
                                  $isData = $incoterm::where('Codigo', $ICPD)->get();
                                  if (count($isData) == 0) {
                                      $vmodel = new \App\Incote();
                                      $vmodel->setConnection('mysql2');
                                      $vmodel->Codigo = $ICPD;
                                      $vmodel->save();
                                      $model->ICPD = $vmodel->ID_Incoterm;
                                      $mensaje = 'Se ha creado un nuevo registro en Incote con el ID '.$vmodel->ID_Incoterm;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  } else {
                                      $model->ICPD = $isData[0]->ID_Incoterm;
                                  }
                                  $model->LIPD = $emapData[35];
                                  $model->PESP = $emapData[36];
                                  $CTSP = $emapData[37];
                                  $centro = new \App\Centro();
                                  $centro->setConnection('mysql2');
                                  $isData = $centro::where('ID_Empresa', $ID_Empresa)->where('ID_Grendi', $GRENDI)->where('Codigo', $CTSP)->get();
                                  if (count($isData) == 0) {
                                      /*     $grendi              = new \App\LISEMP;
                                             $grendi->setConnection('mysql2');
                                             $isData  = $grendi::where('ID_Empresa', $CTSP)->get();
                                             if(count($isData) == 0){
                                                  $grendi              = new \App\LISEMP;
                                                  $grendi->setConnection('mysql2');
                                                  $grendi->ID_Empresa   = $CTSP;
                                                  $grendi->save();
                                             }
                                              */
                                      $vmodel = new \App\Centro();
                                      $vmodel->setConnection('mysql2');
                                      $vmodel->ID_Empresa = $ID_Empresa;
                                      $vmodel->ID_Grendi = $GRENDI;
                                      $vmodel->save();
                                      $model->CTSP = $vmodel->ID_Centro;
                                      $mensaje = 'Se ha creado un nuevo registro en Centro con el ID '. $vmodel->ID_Centro;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  } else {
                                         $model->CTSP = $isData[0]->ID_Centro;
                                  }
                                  $model->DSCT = $emapData[38];
                                  $model->UNPD = $emapData[39];
                                  $model->UMPD = $emapData[40];
                                  $model->PNPD = $emapData[41];
                                  $model->PBPD = $emapData[42];
                                  $model->VOPD = $emapData[43];
                                  $model->VLPD = $emapData[44];
                                  $model->MOPD = $emapData[45];
                                  $model->PLPD = $emapData[46];
                                  $model->STEN = $emapData[47];
                                  $is_saved = $model->save();
                                  if ($is_saved) {
                                      echo 'Archivo '.$filepart[0].' ha sido procesado<br>';
                                  } else {
                                      echo 'No fue guardado.<br/>';
                                  }
                              }
                              ++$count;
                          }
                          break;
                      case 'BDIT':
                          while (($emapData = fgetcsv($file, ',')) !== false) {
                              if ($count != 0) {
                                  $model = new \App\Bdit();
                                  //$model->NRPD     = $emapData[0];
                                  $model->POIT = $emapData[1];
                                  $model->MTPD = $emapData[2];
                                  $model->DSMT = $emapData[3];
                                  $model->MT13 = $emapData[4];
                                  $model->MT14 = $emapData[5];
                                  $model->CPIT = $emapData[6];
                                  $model->UMIT = $emapData[7];
                                  $model->PNIT = $emapData[8];
                                  $model->PBIT = $emapData[9];
                                  $model->VOIT = $emapData[10];
                                  $model->VLIT = $emapData[11];
                                  $model->MOIT = $emapData[12];
                                  $model->PLIT = $emapData[13];
                                  $model->CEIT = $emapData[14];
                                  $fdate = \App\Bdit::convertDate($emapData[14]);
                                  $model->FEIT = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[15]);
                                  $model->FPIT = $fdate;
                                  $model->STIP = $emapData[16];
                                  $model->ENIT = $emapData[17];
                                  $model->POEN = $emapData[18];
                                  $fdate = \App\Bdit::convertDate($emapData[19]);
                                  $model->FEEN = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[20]);
                                  $model->HREN = $fdate;
                                  $model->CPEN = $emapData[21];
                                  $model->CTEN = $emapData[22];
                                  $model->STIT = $emapData[23];
                                  $model->BQEN = $emapData[24];
                                  $model->DSBQ = $emapData[25];
                                  $model->BQFA = $emapData[26];
                                  $model->DSBF = $emapData[27];
                                  $model->MTEN = $emapData[28];
                                  $model->DSEN = $emapData[29];
                                  $model->EN13 = $emapData[30];
                                  $model->EN14 = $emapData[31];
                                  //$model->CPCP     = $emapData[32];
                                  $model->SHID = $emapData[33];
                                  $model->CCEN = $emapData[34];
                                  $fdate = \App\Bdit::convertDate($emapData[35]);
                                  $model->FECE = $fdate;
                                  $model->HRCE = $emapData[36];
                                  $model->CPCE = $emapData[37];
                                  $model->PNCC = $emapData[38];
                                  $model->PBCC = $emapData[39];
                                  $model->VOCC = $emapData[40];
                                  $model->PLCC = $emapData[41];
                                  $model->ENPG = $emapData[42];
                                  $fdate = \App\Bdit::convertDate($emapData[43]);
                                  $model->PGFE = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[44]);
                                  $model->PGHR = $fdate;
                                  $model->PGUS = $emapData[45];
                                  $model->CDEN = $emapData[46];
                                  $model->ENRE = $emapData[47];
                                  $fdate = \App\Bdit::convertDate($emapData[48]);
                                  $model->REFE = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[49]);
                                  $model->REHR = $fdate;
                                  $model->REUS = $emapData[50];
                                  $model->ENFA = $emapData[51];
                                  $model->ENFE = $emapData[52];
                                  $model->FATY = $emapData[53];
                                  $fdate = \App\Bdit::convertDate($emapData[54]);
                                  $model->FAFE = $fdate;
                                  $fdate = \App\Bdit::convertDate($emapData[55]);
                                  $model->FAHR = $fdate;
                                  $model->FAUS = $emapData[56];
                                  $model->CTFA = $emapData[57];
                                  $model->PNFA = $emapData[58];
                                  $model->PBFA = $emapData[59];
                                  $model->VOFA = $emapData[60];
                                  $model->VLFA = $emapData[61];
                                  $model->PLFA = $emapData[62];
                                  $isData = \App\Lismat::where('ID_Empresa', $ID_Empresa)->where('ID_Grendi', $GRENDI)->where('Sku', $model->MTPD)->get();
                                  if (count($isData) == 0) {
                                      $isEmpresa = \App\Lisemp::where('ID_Empresa', $ID_Empresa)->get();
                                      if (count($isEmpresa) == 0) {
                                          $lisemp = new \App\Lisemp();
                                          $lisemp->setConnection('mysql2');
                                          $lisemp->ID_Empresa = $ID_Empresa;
                                          $lisemp->save();
                                          $mensaje = 'Se ha creado un nuevo registro en LISMAT con el ID '. $ID_Empresa;
                                          Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                              $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                          });
                                      }
                                      $isGrendi = \App\Grendi::where('ID_Empresa', $ID_Empresa)->where('ID_Grendi', $GRENDI)->get();
                                      if (count($isGrendi) == 0) {
                                          $grendi = new \App\Grendi();
                                          $grendi->setConnection('mysql2');
                                          $grendi->ID_Empresa = $ID_Empresa;
                                          $grendi->ID_Grendi = $GRENDI;
                                          $grendi->save();
                                          $mensaje = 'Se ha creado un nuevo registro en GRENDI con el ID '. $GRENDI . ' y la el ID de empresa ' . $ID_Empresa;
                                          Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                              $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                          });
                                      }
                                      $model1 = new \App\Lismat();
                                      $model1->setConnection('mysql2');
                                      $model1->ID_Empresa = $ID_Empresa;
                                      $model1->ID_Grendi = $GRENDI;
                                      $model1->Sku = $model->MTPD;
                                      $model1->Descripcion = $model->DSMT;
                                      $model1->UOM = $model->UMIT;
                                      if ($model->CPIT != 0) {
                                        $PesoNeto = $model->PNIT / $model->CPIT;
                                        $model1->PesoNeto = $PesoNeto;
                                      }
                                      $model1->PesoBruto = $model->MTPD;
                                      if ($model->CPIT != 0) {
                                        $Volumen = $model->VOIT / $model->CPIT;
                                        $model1->Volumen = $Volumen;
                                      }
                                      if ($model->CPIT != 0) {
                                        $CantxPallet = $model->PLIT / $model->CPIT;
                                        $model1->CantxPallet = $CantxPallet;
                                      }
                                      $is_saved = $model1->save();
                                      if ($is_saved) {
                                          $model->MTPD = $model1->ID_Mercaderia;
                                      }
                                  } else {
                                      $model->MTPD = $isData[0]->ID_Mercaderia;
                                  }
                                  $is_saved = $model->save();
                              }
                              ++$count;
                          }
                              break;
                      case 'BDSH':
                      while (($emapData = fgetcsv($file, ',')) !== false) {
                          if ($count != 0) {
                              $model = new \App\Bdsh();
                              $lisemp = new \App\Lisemp();
                              $lisemp->setConnection('mysql2');
                              $is_count = $lisemp::where('ID_Empresa', $ID_Empresa)->get();
                              if (count($is_count) == 0) {
                                  $lisemp = new \App\Lisemp();
                                  $lisemp->setConnection('mysql2');
                                  $lisemp->ID_Empresa = $ID_Empresa;
                                  $lisemp->save();
                              }
                              $grendi = new \App\Grendi();
                              $grendi->setConnection('mysql2');
                              $is_count = $grendi::where('ID_Grendi', $GRENDI)->get();
                              if (count($is_count) == 0) {
                                  $grendi = new \App\Grendi();
                                  $grendi->setConnection('mysql2');
                                  $grendi->ID_Grendi = $GRENDI;
                                  $grendi->ID_Empresa = $ID_Empresa;
                                  $grendi->save();
                              }
                              $model->TTEN = $emapData[0];
                              $model->FETT = $emapData[1];
                              $model->HRTT = $emapData[2];
                              $model->CPTT = $emapData[3];
                              //$model->PTTT     = $emapData[4];
                              $model->ETDS = $emapData[6];
                              $model->ETDR = $emapData[7];
                              //$model->ETLO     = $emapData[8];
                              $model->ETCP = $emapData[9];
                              $model->ETPA = $emapData[10];
                              $model->ETTE = $emapData[11];
                              if ($emapData[12] == '') {
                                $emapData[12] = 0;
                              }
                              if ($emapData[10] == '') {
                                $emapData[10] = 'AR';
                              }
                              $isEmptte = \App\Emptte::where('Cuit', $emapData[12])->get();
                              if (count($isEmptte) == 0) {
                                  $emptte = new \App\Emptte();
                                  $emptte->setConnection('mysql2');
                                  $emptte->Cuit = $emapData[12]; //ETCU
                                  $emptte->RazonSocial = $emapData[6];
                                  $emptte->Direccion = $emapData[7];
                                  $emptte->CP = $emapData[9];
                                  $emptte->ID_Pais = $emapData[10];
                                  $emptte->Telefono = $emapData[11];
                                  $is_saved = $emptte->save();
                                  if ($is_saved) {
                                      $ID_Transporte = $emptte->ID_Transporte;
                                      $mensaje = 'Se ha creado un nuevo registro en EMPTTE con el ID '. $emptte->ID_Transporte;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  }
                              } else {
                                  $ID_Transporte = $isEmptte[0]->ID_Transporte;
                              }
                              $model->ETCD = $emapData[13];
                              //$model->TTET = $emapData[5];
                              $ID_Transporte = \App\Emptte::where('Cuit', $emapData[12])->get();
                              $ID_Transporte = $ID_Transporte[0]->ID_Transporte;
                              //where('ProveedorERP', $emapData[5])->
                              $isEmrtte = \App\Emrtte::where('ID_Transporte', $ID_Transporte)->where('ID_Empresa', $ID_Empresa)->where('ID_Grendi', $GRENDI)->get();
                              if (count($isEmrtte) == 0) {
                                  $emrtte = new \App\Emrtte();
                                  $emrtte->ID_Empresa = $ID_Empresa;
                                  $emrtte->ID_Grendi = $GRENDI;
                                  $emrtte->ID_Transporte = $ID_Transporte;
                                  $emrtte->ProveedorERP = $emapData[5];
                                  $emrtte->ClienteERP = $emapData[9];
                                  $emrtte->PerContacto = $emapData[14];
                                  $emrtte->TelContacto = $emapData[16];
                                  $emrtte->CelContacto = $emapData[20];
                                  $emrtte->MailContacto = $emapData[15];
                                  $is_saved = $emrtte->save();
                                  if ($is_saved) {
                                      $model->TTET = $emrtte->ID_Transporte;
                                      $mensaje = 'Se ha creado un nuevo registro en EMRTTE con el ID '. $emrtte->ID_Transporte;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  }
                              } else {
                                  $model->TTET = $isEmrtte[0]->ID_Transporte;
                              }
                              $model->ETPC = $emapData[14];
                              $model->ETMC = $emapData[15];
                              $model->ETTC = $emapData[16];
                              $model->ETCC = $emapData[17];
                              $model->ETUN = $emapData[18];
                              $isDominio = \App\Unitte::where('Dominio', $emapData[19])->get();
                              if (count($isDominio) == 0) {
                                  $unitte = new \App\Unitte();
                                  $unitte->setConnection('mysql2');
                                  $unitte->Dominio = $emapData[19]; //Dominio UNCH
                                  $unitte->ID_Transporte = $ID_Transporte;
                                  $unitte->ID_Satelital = $emapData[20];
                                  $is_saved = $unitte->save();
                                  if ($is_saved) {
                                      $model->UNID = $unitte->ID_UnidadTransporte;
                                      $mensaje = 'Se ha creado un nuevo registro en UNID con el ID '. $unitte->ID_UnidadTransporte;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  }
                              } else {
                                  $model->UNID = $isDominio[0]->ID_UnidadTransporte;
                              }
                              $model->UNID = $emapData[20];
                              $model->UNTC = $emapData[21];
                              $model->UNCP = $emapData[22];
                              $model->UNCK = $emapData[23];
                              $model->UNCV = $emapData[24];
                              $model->UNCO = $emapData[25];
                              //$model->COCU = $emapData[26];
                              $isChofer = \App\Chofer::where('Cuil', $emapData[26])->get();
                              if (count($isChofer) == 0) {
                                  $chofer = new \App\Chofer();
                                  $chofer->setConnection('mysql2');
                                  $chofer->Cuil = $emapData[26]; //Cuil
                                  $chofer->Apynom = $emapData[27];
                                  $chofer->Telefono = $emapData[28];
                                  $chofer->CodigoERP = $emapData[25];
                                  $chofer->ID_Transporte = $ID_Transporte;
                                  $is_saved = $chofer->save();
                                  if ($is_saved) {
                                      $model->COCU = $chofer->ID_Chofer;
                                      $mensaje = 'Se ha creado un nuevo registro en COCU con el ID '. $chofer->ID_Chofer;
                                      Mail::send('emails.adminNotification', ['mensaje' => $mensaje], function ($message) use ($data) {
                                          $message->to($data['emailTo'])->from($data['from'])->subject('Nuevo Registro!');
                                      });
                                  }
                              } else {
                                  $model->COCU = $isChofer[0]->ID_Chofer;
                              }
                              $ID_Chofer = $isChofer[0]->ID_Chofer;
                              $model->CONO = $emapData[27];
                              $model->COTE = $emapData[28];
                              //$model->COBN = $emapData[29];
                              $isChoemp = \App\Choemp::where('ID_Empresa', $ID_Empresa)->where('ID_Chofer', $ID_Chofer)->get();
                              if (count($isChoemp) == 0) {
                                  $choemp = new \App\Choemp();
                                  $choemp->setConnection('mysql2');
                                  $choemp->ID_Empresa = $ID_Empresa;
                                  $choemp->ID_Chofer = $ID_Chofer;
                                  $choemp->Habilitado = $emapData[29];
                                  $choemp->save();
                              }
                              $model->STTT = $emapData[30];
                              $model->FPIN = $emapData[31];
                              $model->FRIN = $emapData[32];
                              $model->FPIC = $emapData[33];
                              $model->FRIC = $emapData[34];
                              $model->FPFC = $emapData[35];
                              $model->FRFC = $emapData[36];
                              $model->FPDE = $emapData[37];
                              $model->FRDE = $emapData[38];
                              $model->FPIT = $emapData[39];
                              $model->FRIT = $emapData[40];
                              $model->FPFT = $emapData[41];
                              $model->FRFT = $emapData[42];
                              $is_saved = $model->save();
                              if ($is_saved) {
                                  echo 'El archivo '.$filepart[0].' ha sido procesado<br>';
                              } else {
                                echo 'El archivo '.$filepart[0].' no ha sido procesado<br>';
                              }
                          }
                          ++$count;
                      }
                 }
                fclose($file);
                echo 'El archivo ' . $filename . ' ha sido procesado';
                rename('CSV/'.$filename, 'importedCSV/'.$filename);
            }
        }
    }
}
