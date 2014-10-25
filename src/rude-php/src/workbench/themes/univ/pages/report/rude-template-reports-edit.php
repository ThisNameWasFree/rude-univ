<?

namespace rude;

class template_reports_edit
{
	/** @var mixed */
	private $report = null;

	public function __construct()
	{
		$report_id = (int) get('report_id');

		if (!$report_id or !reports::is_exists($report_id))
		{
			$template = new template_404();
			$template->html();
		}


		$this->report = reports::get($report_id);


		if (!template_session::is_admin() and !template_session::is_editor())
		{
			if (get('ajax'))
			{
				exit(RUDE_AJAX_ACCESS_VIOLATION);
			}

			return false;
		}


		switch (get('task'))
		{
			case 'update': exit((string) reports::update(get('report_id'),
			                                             get('year'),
				                                         get('duration'),
				                                         get('rector'),
				                                         get('registration_number'),
				                                         get('training_form_id'),
				                                         get('qualification_id'),
				                                         get('specialty_id'),
				                                         get('specialization_id')));
				break;

			default:
				$status = false;
				break;
		}


		if (get('ajax'))
		{
			     if ($status) { exit(RUDE_AJAX_OK);    }
			else              { exit(RUDE_AJAX_ERROR); }
		}

		return true;
	}

	public function html()
	{
		template_html::doctype();

		?>
		<html xmlns="http://www.w3.org/1999/html">
		<? template_html::header() ?>

		<body>
		<? template_html::menu() ?>

		<script>
			rude.semantic.init.menu();
			rude.semantic.init.dropdown();
		</script>


		<div id="container">
			<? template_html::sidebar() ?>

			<div id="content" class="ui segment raised square-corners">
				<? $this->main() ?>
			</div>
		</div>

		<? template_html::footer() ?>
		</body>
		</html>
		<?
	}

	public function main()
	{
		?>
		<div id="main">
			<div id="reports-new">
				<div class="ui error form segment square-corners no-shadow">
					<div class="three fields">
						<div class="field">
							<label>Год набора</label>
							<input id="year" name="year" placeholder="<?= date::year() ?>" type="text" value="<?= $this->report->year ?>">
						</div>

						<div class="field">
							<label>Срок обучения (лет)</label>
							<input id="duration" name="duration" placeholder="4" type="text" value="<?= $this->report->duration ?>" onchange="calendar.update();">
						</div>

						<div class="field">
							<label>ФИО ректора</label>
							<input id="rector" name="rector" placeholder="М.П. Батура" type="text" value="<?= $this->report->rector ?>">
						</div>
					</div>

					<div class="field">
						<label>Регистрационный номер учебного плана</label>
						<input id="registration_number" name="registration_number" placeholder="2014.09.20/000" type="text" value="<?= $this->report->registration_number ?>">
					</div>

					<div class="field">
						<div class="ui fluid selection dropdown">
							<div class="default text">Форма обучения</div>
							<i class="dropdown icon"></i>
							<input type="hidden" id="training_form_id" name="training_form_id" value="<?= $this->report->training_form_id ?>">
							<div class="menu">
								<?
									$training_forms = training_forms::get();

									if ($training_forms)
									{
										foreach ($training_forms as $training_form)
										{
											?>
											<div class="item" data-value="<?= $training_form->id ?>"><?= html::escape($training_form->name) ?></div>
											<?
										}
									}
								?>
							</div>
						</div>
					</div>

					<div class="field">
						<div class="ui fluid selection dropdown">
							<div class="default text">Квалификация специалиста</div>
							<i class="dropdown icon"></i>
							<input type="hidden" id="qualification_id" name="qualification_id" value="<?= $this->report->qualification_id ?>">
							<div class="menu">
								<?
									$qualifications = qualifications::get();

									if ($qualifications)
									{
										foreach ($qualifications as $qualification)
										{
											?>
											<div class="item" data-value="<?= $qualification->id ?>"><?= html::escape($qualification->name) ?></div>
											<?
										}
									}
								?>
							</div>
						</div>
					</div>

					<div class="field">
						<div class="ui fluid selection dropdown">
							<div class="default text">Специальность</div>
							<i class="dropdown icon"></i>
							<input type="hidden" id="specialty_id" name="specialty_id" value="<?= $this->report->specialty_id ?>">
							<div class="menu">
								<?
									$specialties = specialties::get();

									if ($specialties)
									{
										foreach ($specialties as $specialty)
										{
											?>
											<div class="item" data-value="<?= $specialty->id ?>"><?= html::escape($specialty->name) ?></div>
											<?
										}
									}
								?>
							</div>
						</div>
					</div>

					<div class="field">
						<div class="ui fluid selection dropdown">
							<div class="default text">Специализация</div>
							<i class="dropdown icon"></i>
							<input type="hidden" id="specialization_id" name="specialization_id" value="<?= $this->report->specialization_id ?>">
							<div class="menu">
								<?
									$specializations = specializations::get();

									if ($specializations)
									{
										foreach ($specializations as $specialization)
										{
											?>
											<div class="item" data-value="<?= $specialization->id ?>"><?= html::escape($specialization->name) ?></div>
											<?
										}
									}
								?>
							</div>
						</div>
					</div>

					<?
					//						$calendar = new ajax_calendar();
					//						$calendar->html();
					?>

					<div class="ui green submit button small" onclick="update();">Сохранить</div>
					<a href="/?page=reports-preview" target="_blank" id="button-preview" class="ui blue submit button small" onclick="preview();">Предпросмотр</a>
					<a href="#" target="_blank" id="button-popup" class="ui blue submit button small" onclick="calendar.popup(); return false;">Календарь</a>

					<div class="ui dimmer page hidden">
						<div id="calendar" class="ui modal large transition hidden">
							<i class="close icon"></i>

							<div class="header">
								Календарь
							</div>

							<div class="content">
								<table class="ui basic table">
									<tr>
										<th rowspan="3">к<br/>у<br/>р<br/>с<br/>ы</th>
										<th colspan="4">Сентябрь</th>
										<th></th>
										<th colspan="3">Октябрь</th>
										<th></th>
										<th colspan="4">Ноябрь</th>
										<th colspan="4">Декабрь</th>
										<th></th>
										<th colspan="3">Январь</th>
										<th></th>
										<th colspan="3">Февраль</th>
										<th></th>
										<th colspan="4">Март</th>
										<th></th>
										<th colspan="3">Апрель</th>
										<th></th>
										<th colspan="4">Май</th>
										<th colspan="4">Июнь</th>
										<th></th>
										<th colspan="3">Июль</th>
										<th></th>
										<th colspan="4">Август</th>
									</tr>
									<tr>
										<td>1</td>
										<td>8</td>
										<td>15</td>
										<td>22</td>
										<td>
											<div class="underline">29</div>
											09
										</td>
										<td>6</td>
										<td>13</td>
										<td>20</td>
										<td>
											<div class="underline">27</div>
											10
										</td>
										<td>3</td>
										<td>10</td>
										<td>17</td>
										<td>24</td>
										<td>1</td>
										<td>8</td>
										<td>15</td>
										<td>22</td>
										<td>
											<div class="underline">29</div>
											12
										</td>
										<td>5</td>
										<td>12</td>
										<td>19</td>
										<td>
											<div class="underline">26</div>
											01
										</td>
										<td>2</td>
										<td>9</td>
										<td>16</td>
										<td>
											<div class="underline">23</div>
											02
										</td>
										<td>2</td>
										<td>9</td>
										<td>16</td>
										<td>23</td>
										<td>
											<div class="underline">30</div>
											03
										</td>
										<td>6</td>
										<td>13</td>
										<td>20</td>
										<td>
											<div class="underline">27</div>
											04
										</td>
										<td>4</td>
										<td>11</td>
										<td>18</td>
										<td>25</td>
										<td>1</td>
										<td>8</td>
										<td>15</td>
										<td>22</td>
										<td>
											<div class="underline">29</div>
											06
										</td>
										<td>6</td>
										<td>13</td>
										<td>20</td>
										<td>
											<div class="underline">27</div>
											07
										</td>
										<td>3</td>
										<td>10</td>
										<td>17</td>
										<td>24</td>
									</tr>
									<tr>
										<td>7</td>
										<td>14</td>
										<td>21</td>
										<td>28</td>
										<td>
											<div class="underline">05</div>
											10
										</td>
										<td>12</td>
										<td>19</td>
										<td>26</td>
										<td>
											<div class="underline">02</div>
											11
										</td>
										<td>7</td>
										<td>16</td>
										<td>23</td>
										<td>30</td>
										<td>7</td>
										<td>14</td>
										<td>21</td>
										<td>28</td>
										<td>
											<div class="underline">04</div>
											01
										</td>
										<td>11</td>
										<td>18</td>
										<td>25</td>
										<td>
											<div class="underline">01</div>
											02
										</td>
										<td>8</td>
										<td>15</td>
										<td>22</td>
										<td>
											<div class="underline">01</div>
											03
										</td>
										<td>8</td>
										<td>15</td>
										<td>22</td>
										<td>29</td>
										<td>
											<div class="underline">05</div>
											04
										</td>
										<td>12</td>
										<td>19</td>
										<td>26</td>
										<td>
											<div class="underline">03</div>
											05
										</td>
										<td>10</td>
										<td>17</td>
										<td>24</td>
										<td>31</td>
										<td>7</td>
										<td>14</td>
										<td>21</td>
										<td>28</td>
										<td>
											<div class="underline">05</div>
											07
										</td>
										<td>12</td>
										<td>19</td>
										<td>26</td>
										<td>
											<div class="underline">02</div>
											08
										</td>
										<td>9</td>
										<td>16</td>
										<td>23</td>
										<td>31</td>
									</tr>

									<?
										if (calendar_items::is_exists($this->report->id))
										{
											$items = calendar_items::get($this->report->id);

											for ($i = 1; $i <= $this->report->duration; $i++)
											{
												?><tr id="generated-<?= $i ?>" class="generated"><td><?= int::to_roman($i) ?></td><?

												for ($j = 1; $j < 53; $j++)
												{
													$val = '';

													foreach ($items as $item)
													{
														if ($item->year == $i and $item->column == $j)
														{
															$val = $item->value;
														}
													}


													?>
													<td>
														<div class="ui form">
															<div class="inline field">
																<input class="<?= $j ?>" type="text" maxlength="2" value="<?= $val ?>">
															</div>
														</div>
													</td>
													<?
												}

												?></tr><?
											}
										}
									?>
								</table>

								<br />

								<a href="#" target="_blank" id="button-save" class="ui blue submit button small" onclick="calendar.save(); return false;">Сохранить</a>
							</div>
						</div>
					</div>

					<div style="display: none">
						<table>
							<tr id="calendar-hidden">
							<?
								for ($i = 1; $i <= 53; $i++)
								{
									?>
									<td>
										<div class="ui form">
											<div class="inline field">
												<input class="<?= $i ?>" type="text" maxlength="2">
											</div>
										</div>
									</td>
									<?
								}
							?>
							</tr>
						</table>
					</div>


					<script>
						function update()
						{
							var report_id = '<?= $this->report->id ?>';

							var report = new Report();


							$.ajax(
							{
								url: '/?page=reports-edit&task=update&ajax=true',

								data:
								{
									report_id:           report_id,

									year:                report.year,
									duration:            report.duration,
									rector:              report.rector,
									registration_number: report.registration_number,
									training_form_id:    report.training_form_id,
									qualification_id:    report.qualification_id,
									specialty_id:        report.specialty_id,
									specialization_id:   report.specialization_id
								},

								success: function (data)
								{
									console.log(data);
								}
							});
						}

						function preview()
						{
							var report = new Report();

							$.ajax(
							{
								url: '/?page=reports-preview&tmp=true',

								data:
								{
									year:                report.year,
									duration:            report.duration,
									rector:              report.rector,
									registration_number: report.registration_number,
									training_form_id:    report.training_form_id,
									qualification_id:    report.qualification_id,
									specialty_id:        report.specialty_id,
									specialization_id:   report.specialization_id
								},

								success: function (data)
								{
									console.log(data);

//									rude.open('/?page=reports-preview');

//									window.open('/?page=reports-preview', '_blank');
								}
							});
						}

						function Report()
						{
							this.year                = $('#year').val();
							this.duration            = $('#duration').val();
							this.rector              = $('#rector').val();
							this.registration_number = $('#registration_number').val();
							this.training_form_id    = $('#training_form_id').val();
							this.qualification_id    = $('#qualification_id').val();
							this.specialty_id        = $('#specialty_id').val();
							this.specialization_id   = $('#specialization_id').val();
						}

						var calendar =
						{
							duration: <? if ($this->report->id and calendar_items::is_exists($this->report->id)) { echo $this->report->id; } else { echo 'null'; } ?>,

							reset: function()
							{
								$('#calendar .generated').remove();
							},

							update: function()
							{
								var duration = $('#duration').val();

								if (calendar.duration === null || calendar.duration != duration)
								{
									calendar.reset();

									var row = $('#calendar-hidden').html();

									for (var i = 1; i <= duration; i++)
									{
										$('#calendar table').append('<tr id="generated-' + i + '" class="generated"><td>' + rude.romanize(i) + '</td>' + row + '</tr>');
									}
								}

								calendar.duration = duration;
							},

							popup: function()
							{
								if (calendar.duration === null)
								{
									calendar.update();
								}

								$('#calendar').modal('show').modal('cache sizes');

								setTimeout(function() {
									$('#calendar').modal('refresh');
								}, 750);
							},

							get: function()
							{
								var result = [];

								for (var i = 1; i <= calendar.duration; i++)
								{
									var selector = '#generated-' + i;

									if ($(selector).length)
									{
										var cols = $(selector + ' td').length;

										for (var j = 1; j < cols; j++)
										{
											if ($(selector + ' .' + j).val() !== '')
											{
												result.push([i, j, $(selector + ' .' + j).val()]);
											}
										}
									}
								}

								return result;
							},

							save: function()
							{
								var data = calendar.get();

								$.ajax(
								{
									url: '/?page=calendar&task=save&ajax=true',

									data:
									{
										data: data,
										report_id: <?= $this->report->id ?>
									},

									success: function (data)
									{
										console.log(data);
									}
								});
							}
						}
					</script>
				</div>
			</div>
		</div>
		<?
	}
}