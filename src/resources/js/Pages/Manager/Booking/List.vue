<template>
<!-- eslint-disable -->

	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>
			<div class="focus-within:content flex-grow flex flex-col">
				<div class="text-4xl font-bold mb-6 flex justify-between">

					<div class="flex items-center">
						<h1>Turnos</h1>
					</div>
					<div class="flex text-sm">
						<button class="px-3 py-2 border border-transparent rounded-lg shadow-sm text-white bg-blue-500 hover:bg-blue-700 
								       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
								@click=" form = {},
										editingBooking = false,
										viewBooking = false,
										open = true">
							<span>Nuevo Turno </span>
						</button>
					</div>
				</div>

				<div class="lg:flex lg:items-center lg:justify-between mb-4">
					<div class="min-w-0 flex-2 mr-2">
						<Datepicker id="date" class="w-full" v-model="search_date" :enableTimePicker="false"
							:monthChangeOnScroll="false" autoApply :format="format">
						</Datepicker>
					</div>
					<div class="min-w-0 flex-1 ml-2">
						<input class="shadow-sm text-sm border-gray-300 rounded-md" type="text" id="search"
							v-model="search" placeholder="Buscar...">
						<button
							class="ml-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
							@click="getBookings()">Buscar</button>
					</div>

					<div class="mt-5 flex lg:mt-0 lg:ml-4">
						<label class="font-semibold mr-2 mt-2" for="">Ver: </label>
						<select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getBookings">
							<option value="2">2</option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
				</div>
				<!--   TABLA -->
				<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
					<table class="w-full whitespace-nowrap">
						<tr class="text-left font-bold bg-indigo-600 text-white">
							<!-- <th scope="col" class="py-3 px-6" @click="sort_by = 'id', sortBookings()">
								<div class="flex items-center justify-center">
									ID
									<Icons v-if="sort_by == 'id' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'id' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th> -->
							<th scope="col" class="py-3 px-6" @click="sort_by = 'date', sortBookings()">
								<div class="flex items-center justify-center">
									Fecha
									<Icons v-if="sort_by == 'date' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'date' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6 text-left" @click="sort_by = 'fullname', sortBookings()">
								<div class="flex items-center">
									Nombre
									<Icons v-if="sort_by == 'fullname' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'fullname' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6 text-left">
								<div class="flex items-center">
									Documento y Nro Afiliado
									<!-- <Icons v-if="sort_by == 'name' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'name' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" /> -->
								</div>
							</th>
							<th scope="col" class="py-3 px-6" @click="sort_by = 'status', sortBookings()">
								<div class="flex items-center justify-center">
									Estado
									<Icons v-if="sort_by == 'status' && sort_order == 'ASC'" name="bars-up"
										class="h-4 w-4 ml-2" />
									<Icons v-else-if="sort_by == 'status' && sort_order == 'DESC'" name="bars-down"
										class="h-4 w-4 ml-2" />
									<Icons v-else name="bars" class="h-4 w-4 ml-2" />
								</div>
							</th>
							<th scope="col" class="py-3 px-6 text-center">
								Accion
							</th>
						</tr>
						<tr v-for="booking in bookings.data" class="bg-white border-b text-center text-sm ">
							<!-- <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
								{{ booking.id }}
							</th> -->
							<td class="py-4 px-6">
								{{ printDate(booking.date) }}
							</td>
							<td class="py-4 px-6 text-left capitalize">
								<p class="text-medium" v-if="booking.contact.fullname">{{ booking.contact.fullname }} </p>
								<p class="text-medium" v-else>{{ booking.contact.name }}</p>
								<div class="text-gray-400 flex"><PhoneIcon class="w-4 mr-1" /><span>{{ booking.contact.wa_id }}</span></div>
							</td>

							<td class="py-4 px-6  text-left ">
								<p> Doc: {{ booking.contact.nro_doc }}</p>
								<p> Nro: {{ booking.contact.nro_affiliate }}</p>
							</td>

							<td class="py-4 px-6">
								<!-- <span
									class="inline-flex items-center justify-center px-2 py-1 mr-2 text-sm  leading-none rounded-md"
									:class="booking.status.status == 'AGENDADO' ? 'text-blue-100 bg-blue-500' : [
											booking.status.status == 'CANCELADO' ? 'text-red-100 bg-red-600' : 'text-green-100 bg-green-600']">
									{{ booking.status.status }}
								</span> -->
								<span
									class="inline-flex items-center justify-center px-3 py-1 mr-2 text-xs tracking-wider rounded-sm capitalize"
									:class="labelStatus[booking.status.status]">
									{{ booking.status.status }}
								</span>

							</td>
							<td class="py-4 px-6">
								<div >
									<div v-if="booking.status.status == 'agendado'" @click="changeStatus(booking.id, 3)" class="inline-flex items-center p-2 rounded-md cursor-pointer mr-2
											  text-green-900 bg-green-100 hover:bg-green-200 "><Icons name="clip-check" class="h-4 w-4 mr-1"/> Atendido</div>
									<div @click="detailBooking(booking)" class="inline-flex items-center p-2 rounded-md cursor-pointer
											  text-gray-600 bg-gray-100 hover:bg-gray-200 "><Icons name="view" class="h-4 w-4 mr-1"/> Detalle</div>
								</div>

							</td>

						</tr>
					</table>
					<div class="flex justify-between mx-5 px-2 items-center p-2 text-sm  ">
						<div>
							Mostrando: {{ this.bookings.from }} a {{ this.bookings.to }} - Entradas encontradas:
							{{ this.bookings.total }}
						</div>

						<div class="flex flex-wrap -mb-1">
							<template v-for="link in bookings.links">
								<div v-if="link.url === null"
									class="mr-1 mb-1 px-3 py-2 text-xs leading-4 text-gray-400 border rounded-md"
									v-html="link.label"> </div>
								<div v-else
									class="mr-1 mb-1 px-3 py-2 text-xs leading-4 border border-gray-300 rounded-md hover:bg-indigo-500 hover:text-white cursor-pointer"
									:class="{ 'bg-indigo-500': link.active }, { 'text-white': link.active }"
									@click="getBookingsPaginate(link.url)" v-html="link.label"> </div>
							</template>
						</div>
					</div>
				</div>

			</div>
		</template>


	</AppLayout>

	<TransitionRoot as="template" :show="open">
		<Dialog as="div" class="fixed inset-0 overflow-hidden" @close="open = false">
			<div class="absolute inset-0 overflow-hidden">
				<DialogOverlay class="absolute inset-0" />

				<div class="fixed inset-y-0 pl-16 max-w-full right-0 flex">
					<TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
						enter-from="translate-x-full" enter-to="translate-x-0"
						leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0"
						leave-to="translate-x-full">
						<div class="w-screen max-w-md">
							<form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
								<div class="flex-1 h-0 overflow-y-auto">
									<div class="py-7 px-4 bg-indigo-300 sm:px-6">
										<div class="flex items-center justify-between">
											<DialogTitle v-if="editingBooking == false && viewBooking == false"
												class="text-lg font-medium text-white">
												Nuevo Turno
											</DialogTitle>

											<DialogTitle v-else-if="editingBooking == true && viewBooking == false"
												class="text-lg font-medium text-white"> Editar Turno </DialogTitle>

											<DialogTitle v-else class="text-lg font-medium text-white"> Detalle Turno </DialogTitle>
											<div class="ml-3 h-7 flex items-center">
												<div class="text-indigo-100 hover:text-white"
														@click.prevent="open = false"><Icons name="x" class="h-5 w-5"></Icons></div>
											</div>
										</div>
									</div>
									<div class="flex-1 flex flex-col justify-between">
										<div class="px-4 sm:px-6 font-medium">

											<div class="mt-2">
												<label for="date"
													class="block text-sm font-medium text-gray-900">Fecha de Turno</label>
												<div class="mt-1">
													<Datepicker id="date"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md bg-red-100"
														v-model="form.date" :enableTimePicker="false"
														:monthChangeOnScroll="false" autoApply :format="format">
													</Datepicker>
												</div>
											</div>

											<div class="mt-4">
												<label for="fullname"
													   class="block text-sm font-medium text-gray-900">Nombre y Apellido</label>
												<div class="mt-1">
													<input type="text" v-model="form.fullname" name="fullname"
														id="fullname"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md" />
												</div>
											</div>
											
											<div class="mt-2">
												<label for="telefono"
													   class="block text-sm font-medium text-gray-900">Documento</label>
												<div class="mt-1">
													<input type="text" v-model="form.nro_doc" name="nro_doc" id="nro_doc"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500  rounded-md" />
												</div>
											</div>

											<div class="mt-2">
												<label for="nro_affiliate"
													class="block text-sm font-medium text-gray-900">Nro. Afiliado</label>
												<div class="mt-1">
													<input type="text" v-model="form.nro_affiliate" name="nro_affiliate"
														id="nro_affiliate"
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md" />
												</div>
											</div>
											
											<div class="mt-2">
												<label for="fullname"
													class="block text-sm font-medium text-gray-900">Nombre en WhatsApp</label>
												<div class="mt-1">
													<input type="text" v-model="form.name" name="name" id="name" disabled
														class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md bg-gray-50" />
												</div>
											</div>

											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Teléfono</label>
												<div class="mt-1">
													<input type="text" v-model="form.wa_id" name="wa_id" id="wa_id" disabled 														
														   class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md bg-gray-50" />
												</div>
											</div>

											<div class="mt-2">
												<label for="status"
													class="block text-sm font-medium text-gray-900">Estado</label>
												<div class="mt-1 flex justify-between">
													<input type="text" v-model="form.status" name="status" id="status" disabled 														
														   class="block w-60 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 rounded-md bg-gray-50" />
													<button v-if="form.status == 'agendado'" @click.prevent="changeStatus(form.id, 3)" class="px-4 py-1 bg-green-300 text-green-900 rounded-md hover:bg-green-400"> Confirmar Visita</button>
												</div>
											</div>

										</div>
										<!-- <div class="px-4 sm:px-6 font-medium" v-else>

											<div class="mt-4">
												<label for="fullname"
													class="block text-sm font-medium text-gray-900">Nombre y
													Apellido: {{ form.fullname }}</label>
											</div>
											<div class="mt-2">
												<label for="nro_affiliate"
													class="block text-sm font-medium text-gray-900">Nro.
													Afiliado: {{ form.nro_affiliate }}</label>
											</div>
											<div class="mt-2">
												<label for="fullname"
													class="block text-sm font-medium text-gray-900">WhatsApp: {{ form.name }}</label>
											</div>
											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Telefono: {{ form.wa_id }}</label>
											</div>
											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Nro.
													Documento: {{ form.nro_doc }}</label>
											</div>

											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Fecha: {{ printDate(form.date) }} </label>
											</div>

											<div class="mt-2">
												<label for="telefono"
													class="block text-sm font-medium text-gray-900">Estado: {{ form.status }}</label>
											</div>

										</div> -->
									</div>
								</div>

								<div class="flex-shrink-0 px-4 py-4 flex justify-between">
									<div>
										<button type="button" v-if="form.status == 'AGENDADO'"
 												class="bg-white py-2 px-4 border border-red-400 rounded-md shadow-sm text-sm font-medium text-red-700 
													   hover:border-transparent hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
												@click="changeStatus(form.id, 2)">Cancelar Turno</button>
									</div>									
									<div>
										<button type="button"
											class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
											@click="open = false">Cerrar</button>

										<button @click.prevent="storeBooking" v-if="!viewBooking"
											class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
											Guardar
										</button>

									</div>
								</div>

							</form>
						</div>
					</TransitionChild>
				</div>
			</div>
		</Dialog>
	</TransitionRoot>
</template>


<script>
import { CheckCircleIcon, ChevronRightIcon, MailIcon,PhoneIcon } from '@heroicons/vue/solid'
import AppLayout from '@/Layouts/AppLayout.vue';
import moment from 'moment';
import Icons from '@/Layouts/Components/Icons.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Toast from '@/Layouts/Components/Toast.vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'


const labelStatus = {
	'agendado' : 'bg-indigo-50 text-indigo-800 border border-indigo-300',
	'finalizado' : 'bg-green-50 text-green-800 border border-green-400',
	'cancelado' : 'bg-red-50 text-red-800 border border-red-300',
}

const statusIcon = {
	'agendado' : 'bg-indigo-500 text-white',
	'confirmado' : 'bg-green-500 text-white',
	'atendido' : 'bg-yellow-500 text-white'
}

export default {
	props: {
		booking_status: Object
	},

	components: {
		AppLayout,
		CheckCircleIcon, ChevronRightIcon, MailIcon,
		moment,
		Icons,
		Datepicker,
		Toast,
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot,
		PhoneIcon
	},
	setup() {
		const format = (date) => {
			const day = date.getDate();
			const month = date.getMonth() + 1;
			const year = date.getFullYear();

			return `${day}/${month}/${year}`;
		}
		return {
			format,
			labelStatus,
			statusIcon			
		}
	},
	data() {
		return {
			bookings: "",
			length: "10",
			sort_order: 'DESC',
			sort_by: "id",
			search: "",
			search_date: "",
			loading: false,
			open: false,
			form: {},
			toastMessage: "",
			labelType: "info",
			editingUser: false,
			viewBooking: false,

		}
	},

	created() {
		this.getBookings()
	},
	
	methods: {

		detailBooking(booking){
			console.log(booking)
			this.form.id = booking.id,
			this.form.fullname = booking.contact.fullname,
			this.form.name = booking.contact.name,
			this.form.wa_id = booking.contact.wa_id,
			this.form.nro_doc = booking.contact.nro_doc,
			this.form.nro_affiliate = booking.contact.nro_affiliate,
			this.form.status_id = booking.status.id,
			this.form.status = booking.status.status
			this.form.date = this.formatDate(booking.date)

			if(booking.status.id == 1){
				this.viewBooking = false
				this.editingBooking = true
			}else{	
				this.viewBooking = true
				this.editingBooking = false
			}

			this.open = true
		},

		clearMessage() {
			this.toastMessage = ""
		},
		formatDate(date){
			return new Date(date + "T00:00:00.000-03:00")
		},
		printDate(date){
			return moment(date, 'Y/mm/D').format('D/mm/Y')
		},
		async getBookings() {

			this.loading = true
			this.bookings = ""
			let filter = `&length=${this.length}`
			filter += `&sort_by=${this.sort_by}`
			filter += `&sort_order=${this.sort_order}`

			if (this.search.length > 0) {
				filter += `&search=${this.search}`
			}

			if (this.search_date > 0) {
				filter += `&search_date=${JSON.stringify(this.search_date)}`
			}

			const get = `${route('booking.list')}?${filter}`

			const response = await fetch(get, { method: 'GET' })
			this.bookings = await response.json()
			this.loading = false
		},
		async getBookingsPaginate(link) {

			var get = `${link}`;
			const response = await fetch(get, { method: 'GET' })

			this.bookings = await response.json()

		},
		sortBookings() {
			this.sort_order = this.sort_order === 'ASC' ? 'DESC' : 'ASC'
			this.getBookings()
		},

		async changeStatus(id, status_id){

			let response = await axios.post('/booking/updatestatus', {
												id: id,
												status_id: status_id
											})

			if (response.status == 200) {
				this.open = false
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.getBookings()
			} else {
				this.open = false
				this.toastMessage = response.data.message
				this.labelType = "danger"
			}

		},

		// async _changeStatus(id, action){

		// 	let response = await axios.post('/booking/updatestatus', {
		// 										id: id,
		// 										status: action
		// 									})


		// 	if (response.data.status == 'success') {
		// 		this.toastMessage = response.data.message
		// 		this.labelType = "success"
		// 	} else {
		// 		this.toastMessage = response.data.message
		// 		this.labelType = "error"
		// 	}

		// },

		async updateStatus(id, status) {

			let rt = route('booking.updatestatus');
			let status_id = ''

			switch (status) {
				case 1:
					status_id = this.booking_status.filter(function (obj) {
						if (obj.status == 'AGENDADO') {
							return obj.id
						}
					})
					break;
				case 2:
					status_id = this.booking_status.filter(function (obj) {
						if (obj.status == 'ATENDIDO') {
							return obj.id
						}
					})
					break;
				case 3:
					status_id = this.booking_status.filter(function (obj) {
						if (obj.status == 'CANCELADO') {
							return obj.id
						}
					})
					break;
				default:
					break;
			}

			this.form.status_id = status_id[0].id
			this.form.id = id

			axios.post(rt, {
				form: this.form,
			}).then(response => {
				this.open = false
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.getBookings()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},

		async storeBooking() {
			let rt = ''
			if (this.editingBooking) {
				rt = route('booking.updatebooking');
			} else {
				rt = route('booking.createbooking');
			}

			axios.post(rt, {
				form: this.form,
			}).then(response => {
				this.open = false
				this.labelType = "success"
				this.toastMessage = response.data.message
				this.getBookings()
			}).catch(error => {
				this.labelType = "danger"
				this.toastMessage = error.response.data.message
			})
		},
	}
}
</script>