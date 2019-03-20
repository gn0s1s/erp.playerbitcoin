<table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th data-hide="phone">ID</th>
										<th data-class="expand">Voucher Date</th>
										<th data-hide="phone">User</th>
                                        <th data-hide="phone,tablet">Payment Method</th>
										<th data-hide="phone">Account</th>
										<th data-hide="phone">Owner</th>
										<th data-hide="phone">Country</th>
										<th data-hide="phone">Swift</th>
										<th data-hide="phone">Wallet Address</th>
										<th data-hide="phone">Postal address</th>
										<th data-hide="phone,tablet">Amount</th>
										<th data-hide="phone,tablet">Status</th>
                                        <th data-hide="phone,tablet">options</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($cobros as $cobro) {?>
										<tr>
											<td><?php echo $cobro->id_cobro; ?></td>
											<td><?php echo $cobro->fecha; ?></td>
											<td><?php echo $cobro->usuario; ?></td>
											<td><?php echo $cobro->banco; ?></td>
											<td><?php echo $cobro->cuenta; ?></td>
											<td><?php echo $cobro->titular; ?></td>
											<td><?php echo $cobro->pais; ?></td>
											<td><?php echo $cobro->swift; ?></td>
											<td><?php echo $cobro->address; ?></td>
											<td><?php echo $cobro->postal; ?></td>
											<td>$ <?php echo number_format($cobro->monto,2); ?></td>
											<td><?php echo $cobro->estado; ?></td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-danger"
                                                   onclick="removerCobro(<?=$cobro->id_cobro?>)">Abort Payment</a>
                                                <?php if($cobro->banco == "BLOCKCHAIN"):?>
                                                    <a href="javascript:void(0)" class="btn btn-success"
                                                       onclick="pagarBlockchain(<?=$cobro->id_cobro?>)">Confirm Payment</a>
                                                <?php endif; ?>
                                            </td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
