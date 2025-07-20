document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById('graficaDocumentos').getContext('2d'); 

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Enero', 'Febrero', 'Marzo'], 
      datasets: [
        {
          label: 'Aprobados',
          data: [10, 15, 20], 
          backgroundColor: 'rgba(0, 200, 255, 0.6)'
        },
        {
          label: 'Revisión',
          data: [5, 10, 15],
          backgroundColor: 'rgba(0, 150, 255, 0.6)'
        },
        {
          label: 'Rechazados',
          data: [5, 5, 5], 
          backgroundColor: 'rgba(0, 100, 255, 0.6)'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 10
          }
        }
      }
    }
  });
});
