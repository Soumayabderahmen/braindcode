<script setup>
import { ref, onMounted } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { router } from "@inertiajs/vue3";
import Main from "../../Layouts/main.vue";

const props = defineProps({
    availabilities: Array,
});

const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: props.availabilities.map(avail => ({
        id: avail.id,
        title: avail.statut === 'available' ? 'Disponible' : 'Indisponible',
        start: `${avail.date}T${avail.start_time}`,
        end: `${avail.date}T${avail.end_time}`,
        color: avail.statut === 'available' ? '#28a745' : '#dc3545',
        extendedProps: {
            statut: avail.statut
        }
    })),
    editable: true,
    selectable: true,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    select: handleDateSelect,
    eventClick: handleEventClick,
    eventDrop: handleEventDrop,
    eventResize: handleEventResize
});

function handleDateSelect(selectInfo) {
    const title = prompt('Entrez le statut (available/unavailable):');
    if (title) {
        const startTime = selectInfo.startStr.split('T')[1];
        const endTime = selectInfo.endStr.split('T')[1];

        router.post(route('coach.availability.store'), {
            date: selectInfo.startStr.split('T')[0],
            start_time: startTime.substring(0, 5),
            end_time: endTime.substring(0, 5),
            statut: title,
            coach_id: props.coachId
        }, {
            onSuccess: () => {
                calendarOptions.value.events.push({
                    title,
                    start: selectInfo.startStr,
                    end: selectInfo.endStr,
                    color: title === 'available' ? '#28a745' : '#dc3545'
                });
            }
        });
    }
    calendar.getApi().unselect();
}

function handleEventClick(clickInfo) {
    if (confirm(`Voulez-vous supprimer cette disponibilité?`)) {
        router.delete(route('coach.availability.destroy', clickInfo.event.id), {
            onSuccess: () => {
                clickInfo.event.remove();
            }
        });
    }
}

function handleEventDrop(dropInfo) {
    const event = dropInfo.event;

    router.put(route('coach.availability.updateTimes', event.id), {
        date: event.startStr.split('T')[0],
        start_time: event.startStr.split('T')[1].substring(0, 5),
        end_time: event.endStr.split('T')[1].substring(0, 5)
    });
}

function handleEventResize(resizeInfo) {
    const event = resizeInfo.event;

    router.put(route('coach.availability.updateTimes', event.id), {
        end_time: event.endStr.split('T')[1].substring(0, 5)
    });
}
</script>

<template>
    <Main :showSidebar="true">
        <div class="card" style="
    margin-right: -249px;
">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="right">
                            <div class="card-header">
                                <h2 class="card-title text-center mb-4">Mon Calendrier de Disponibilités</h2>
                            </div>
                            <div class="card-body">
                                <FullCalendar :options="calendarOptions" />
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>

    </Main>
</template>

<style scoped>
.calendar-container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

:deep(.fc-toolbar) {
  background: #34495F;
  color: white;
  border-radius: 8px;
  padding: 10px;
}

:deep(.fc-button) {
  background: #2980b9 !important;
  border: none !important;
  color: white !important;
}

:deep(.fc-daygrid-day) {
  border: 1px solid #ddd;
}

:deep(.fc-daygrid-day-number) {
  color: #2c3e50;
  font-weight: bold;
}
</style>