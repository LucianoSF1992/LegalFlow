
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import api from '../api';

const rows = ref<any[]>([]);
const title = ref('');
const priority = ref('medium');
const due = ref('');
const status = ref('pending');
const message = ref('');

function formatDate(value: string | null) {
  if (!value) return 'Sem prazo';

  const date = value.substring(0, 10);
  const [year, month, day] = date.split('-');

  return `${day}/${month}/${year}`;
}

async function load() {
  rows.value = (await api.get('/tasks')).data.data;
}

async function save() {
  try {
    await api.post('/tasks', {
      title: title.value,
      priority: priority.value,
      status: status.value,
      due_date: due.value || null
    });

    title.value = '';
    due.value = '';
    message.value = 'Tarefa criada.';
    await load();
  } catch {
    message.value = 'Não foi possível criar a tarefa.';
  }
}

async function complete(t: any) {
  await api.put(`/tasks/${t.id}`, { status: 'completed' });
  await load();
}

async function remove(id: number) {
  if (confirm('Excluir esta tarefa?')) {
    await api.delete(`/tasks/${id}`);
    await load();
  }
}

onMounted(load);
</script>

<template>
  <section class="page">
    <div class="page-heading">
      <div>
        <p class="eyebrow">ROTINA DO ESCRITÓRIO</p>
        <h1>Tarefas</h1>
        <p class="muted">Organize atividades, prioridades e prazos.</p>
      </div>
    </div>

    <div class="panel form-panel">
      <h2>Nova tarefa</h2>

      <form class="inline-form" @submit.prevent="save">
        <label class="wide">
          Descrição
          <input v-model="title" required placeholder="Ex.: Preparar petição inicial" />
        </label>

        <label>
          Prioridade
          <select v-model="priority">
            <option value="low">Baixa</option>
            <option value="medium">Média</option>
            <option value="high">Alta</option>
          </select>
        </label>

        <label>
          Prazo
          <input v-model="due" type="date" />
        </label>

        <button class="primary">Criar tarefa</button>
      </form>

      <p v-if="message" class="muted">{{ message }}</p>
    </div>

    <div class="panel">
      <div class="panel-heading">
        <h2>Lista de tarefas</h2>
      </div>

      <div v-if="rows.length" class="task-list">
        <div v-for="t in rows" :key="t.id" class="task-row">
          <span class="task-dot" :class="t.priority"></span>

          <div class="task-main">
            <b>{{ t.title }}</b>
            <small>
              Prazo: {{ formatDate(t.due_date) }} ·
              Prioridade: {{ t.priority }} · {{ t.status }}
            </small>
          </div>

          <button
            v-if="t.status !== 'completed'"
            class="secondary"
            @click="complete(t)"
          >
            Concluir
          </button>

          <button class="danger-link" @click="remove(t.id)">
            Excluir
          </button>
        </div>
      </div>

      <p v-else class="empty">Nenhuma tarefa cadastrada.</p>
    </div>
  </section>
</template>
