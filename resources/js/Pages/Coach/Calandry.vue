<script setup>
import { ref, onMounted } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { router } from "@inertiajs/vue3";
import bootstrap5Plugin from '@fullcalendar/bootstrap5'

const props = defineProps({
    availabilities: Array,
});
onMounted(() => {
  console.log("✅ Données des availabilities :", props.availabilities);
});

const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin,bootstrap5Plugin],
    themeSystem: 'bootstrap5',
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: props.availabilities.map(avail => ({
  id: avail.id,
  title: avail.title,
  start: `${avail.date}T${avail.start_time}`,
  end: `${avail.date}T${avail.end_time}`,
  color: avail.statut === 'available' ? '#28a745' : '#dc3545',
  extendedProps: {
    statut: avail.statut,
    place: avail.nb_place,
    honoraires: avail.honoraire
  }
}))
,
    eventContent: function(arg) {
    const statut = arg.event.extendedProps.statut;
    const backgroundColor = statut === 'available' ? '#28a745' : '#dc3545';
const title = arg.event.title ?? "Sans titre";
    const place = arg.event.extendedProps.place ?? "non défini";
    const honoraires = arg.event.extendedProps.honoraires ?? "non défini";
    const start = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const end = arg.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    return {
        html: `
            
            <div style="background:#3399ff;color:white;padding:4px 6px;border-radius:8px;font-size:12px;">${title}</div>
        `
    };
},


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
    console.log("Titres des disponibilités :", calendarOptions.value.events.map(e => e.title));

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
<!-- <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.css" rel="stylesheet"> -->
<!-- FullCalendar CSS depuis le CDN officiel -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

       

            
                            <div class="card-body">
                                <FullCalendar :options="calendarOptions" />
                            </div>
                    
           
       

    
</template>

<style scoped>
:deep(.fc) {
  font-family: 'Inter', sans-serif;
  background-color: #eef6fd;
  padding: 1rem;
  border-radius: 20px;
}

:deep(.fc-toolbar) {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

:deep(.fc-button) {
  background-color: #e3f0fc !important;
  color: #0066cc !important;
  border: none !important;
  padding: 8px 20px;
  border-radius: 25px !important;
  font-weight: 500;
  text-transform: none;
}

:deep(.fc-button-active) {
  background-color: #0066cc !important;
  color: #fff !important;
}

:deep(.fc-daygrid-day-frame) {
  background-color: white;
  border-radius: 30px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  padding: 0.5rem;
  height: 100%;
  transition: all 0.2s ease-in-out;
  height: 100px;
  width: 140px;
}

:deep(.fc .fc-daygrid-body tr) {
  height: 110px !important;
}

:deep(.fc .fc-daygrid-day-frame) {
  margin-top: 8px !important;
  margin-bottom: 8px !important;
}

:deep(.fc .fc-daygrid-day) {
  padding: 8px;
  border-radius: 16px;
}

:deep(.fc-daygrid-day-number) {
  font-weight: bold;
  color:#3399ff;
}

:deep(.fc-daygrid-event) {
  background-color: #3399ff !important;
  color: white !important;
  font-size: 12px;
  border-radius: 8px;
  padding: 3px 5px;
  white-space: normal;
  word-break: break-word;
  margin-top: 3px;
}

.tippy-box[data-theme~='light'] {
  background-color: white;
  border: 1px solid #eee;
  box-shadow: 0 2px 8px #3399ff;
  border-radius: 12px;
  padding: 0;
  color: #3399ff;
  width: 100px;
  font-size: 13px;
}

</style>
