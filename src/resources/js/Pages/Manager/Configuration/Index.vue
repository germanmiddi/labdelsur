<template>
	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>
			<div class="container mx-auto shadow-lg rounded-lg tracking-normal ">

				<!-- headaer -->
				<div class="px-5 py-5 flex justify-between items-center bg-white border-b-2">
					<div class="font-semibold text-2xl text-gray-700">Panel de Configuraciones</div>
					<div class="w-1/2">
						<!-- <input type="text" name="" id="" placeholder="search IRL" class="rounded-2xl bg-gray-100 py-3 px-5 w-full" /> -->
					</div>
				</div>

				<div
					class="mt-8 max-w-3xl grid grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-6">
					<section aria-labelledby="timeline-title" class="lg:col-span-3">
						<div class="bg-white px-3 py-5 sm:rounded-lg ">
							<h1 class="text-center font-semibold text-2xl">Listado de Feriados</h1>

							<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-2">
								<thead
									class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
									<tr>
										<th scope="col" class="py-3 px-6">
											Fecha
										</th>
										<th scope="col" class="py-3 px-6">
											Detalle
										</th>
										<th scope="col" class="py-3 px-6">
											Acción
										</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="holiday in list_holidays"
										class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center">
										<th scope="row"
											class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
											{{format(holiday.date)}}
										</th>
										<td class="py-4 px-6">
											{{holiday.description}}
										</td>
										<td class="py-4 px-6">
											<a type="button" @click="deleteHoliday(holiday.id)"
												class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
												<Icons name="trash" class="h-5 w-5"></Icons>
											</a>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="mt-6 flex flex-col justify-stretch">
								<a type="button" @click="newHoliday = true" v-show="!newHoliday"
									class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Nuevo
									Feriado</a>
							</div>

							<form v-show="newHoliday" action="#" method="POST">
								<div class="shadow overflow-hidden sm:rounded-md mt-2 ">
									<div class="px-4 py-5 bg-white sm:p-6">
										<div class="grid grid-cols-6 gap-6">

											<div class="col-span-6 sm:col-span-6">
												<label for="google_area1"
													class="block text-sm font-medium text-gray-700">Fecha</label>
												<Datepicker id="date" class="w-full mt-1" v-model="form_holiday.date"
													:enableTimePicker="false" :monthChangeOnScroll="false" autoApply
													:format="format">
												</Datepicker>
											</div>

											<div class="col-span-6 sm:col-span-6">
												<label for="google_postal_code"
													class="block text-sm font-medium text-gray-700">Descripcion
												</label>
												<input type="text" name="description" id="description"
													v-model="form_holiday.description"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
											</div>

											<div class="col-span-6 sm:col-span-6">
												<a type="button" @click="storeHoliday()"
													class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar
												</a>
												<a type="button" @click="newHoliday = false"
													class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cancelar
												</a>
											</div>

										</div>
									</div>

								</div>
							</form>
						</div>
						<br>
					</section>

					<section aria-labelledby="timeline-title" class="lg:col-span-3">
						<div class="bg-white px-3 py-5  sm:rounded-lg">
							<h1 class="text-center font-semibold text-2xl">Configuración por Dia</h1>

							<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400  mt-2">
								<thead
									class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-center">
									<tr>
										<th scope="col" class="py-3 px-6">
											Dia
										</th>
										<th scope="col" class="py-3 px-6">
											Turnos
										</th>
										<th scope="col" class="py-3 px-6">
											Acción
										</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="day in list_days"
										class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 text-center">
										<th scope="row"
											class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
											{{day.description}}
										</th>
										<td class="py-4 px-6">
											{{day.cant_orders}}
										</td>
										<td class="py-4 px-6">
											<button @click="editDay = true,
											form_day.id=day.id,
											form_day.num_day=day.num_day,
											form_day.description=day.description,
											form_day.cant_orders=day.cant_orders"
												class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
												<Icons name="edit" class="h-5 w-5"></Icons>
											</button>
										</td>
									</tr>
								</tbody>
							</table>


							<form v-show="editDay" action="#" method="POST">
								<div class="shadow overflow-hidden sm:rounded-md mt-2">
									<div class="px-4 py-5 bg-white sm:p-6">
										<div class="grid grid-cols-6 gap-6">

											<div class="col-span-6 sm:col-span-6">
												<label for="google_area1"
													class="block text-sm font-medium text-gray-700">Dia</label>
												<input type="text" name="description" id="description"
													v-model="form_day.description"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-50"
													disabled />
											</div>

											<div class="col-span-6 sm:col-span-6">
												<label for="google_postal_code"
													class="block text-sm font-medium text-gray-700">Turnos
												</label>
												<input type="number" name="cant_orders" id="cant_orders"
													v-model="form_day.cant_orders"
													class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
											</div>

											<div class="col-span-6 sm:col-span-6">
												<a type="button" @click="updateDay()"
													class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Actualizar
												</a>
												<a type="button" @click="editDay = false"
													class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Cancelar
												</a>

											</div>

										</div>
									</div>

								</div>
							</form>
						</div>
						<br>
					</section>
				</div>
			</div>
		</template>
	</AppLayout>
</template>


<script>
import { CheckCircleIcon, ChevronRightIcon, MailIcon } from '@heroicons/vue/solid'
import AppLayout from '@/Layouts/AppLayout.vue';
import moment from 'moment';
import Icons from '@/Layouts/Components/Icons.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Toast from '@/Layouts/Components/Toast.vue'

export default {
	props: {
		holidays: Object,
		days: Object
	},

	components: {
		AppLayout,
		CheckCircleIcon, ChevronRightIcon, MailIcon,
		moment,
		Icons,
		Datepicker,
		Toast
	},
	setup() {
		return {
		}
	},
	data() {
		return {
			newHoliday: false,
			editDay: false,
			form_day: {},
			form_holiday: {},
			list_days: this.days,
			list_holidays: this.holidays,
			toastMessage: "",
			labelType: "info",

		}
	},

	mounted() {
		this.list_day = this.days
		this.list_holiday = this.holidays
	},
	methods: {

		format(date) {
			return moment(date).format('DD-MM-YYYY');
		},
		updateDay() {
			console.log('UPDATE' + this.form_day)
			this.editDay = false

			let rt = route('configuration.updateday');

			axios.post(rt, {
				form: this.form_day,
			}).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					this.listDay();
				} else {
					this.labelType = "danger"
					this.toastMessage = response.data.message
				}
			})
		},
		storeHoliday() {
			this.newHoliday = false

			let rt = route('configuration.storeholiday');

			axios.post(rt, {
				form: this.form_holiday,
			}).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					this.listHoliday();
				} else {
					this.labelType = "danger"
					this.toastMessage = response.data.message
				}

			})
		},
		deleteHoliday(id) {
			let rt = route('configuration.deleteholiday', id);
			axios.get(rt).then(response => {
				if (response.status == 200) {
					this.labelType = "success"
					this.toastMessage = response.data.message
					this.listHoliday();
				} else {
					this.labelType = "danger"
					this.toastMessage = response.data.message
				}

			})
		},
		async listHoliday() {
			const get = `${route('configuration.listholiday')}`

			const response = await fetch(get, { method: 'GET' })
			this.list_holidays = await response.json()
		},
		async listDay() {
			const get = `${route('configuration.listday')}`

			const response = await fetch(get, { method: 'GET' })
			this.list_days = await response.json()
		},
	}
}
</script>