fetch('get_forecast.php')
  .then(response => response.json())
  .then(data => {
    const years = data.map(row => row.year);
    const actual = data.map(row => row.actual_population);
    const predicted = data.map(row => row.predicted_population);
    const growth = data.map(row => row.growth_rate);

    const ctx = document.getElementById('populationChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: years,
        datasets: [
          {
            label: 'Actual Population',
            data: actual,
            borderColor: '#2a1aff',
            fill: true
          },
          {
            label: 'Predicted Population',
            data: predicted,
            borderColor: '#ff1a1a',
            borderDash: [5, 5],
            fill: false
          },
          {
            label: 'Growth Rate (%)',
            data: growth,
            borderColor: '#00cc00',
            fill: false,
            yAxisID: 'y2' 
          }
        ]
        
      },
      options: {
        scales: {
          y: { title: { display: true, text: 'Population' } },
          y2: { position: 'right', title: { display: true, text: 'Growth Rate (%)' }, grid: { drawOnChartArea: false } }
        }
      }
    });
  });
