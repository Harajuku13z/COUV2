import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();
Livewire.start();
