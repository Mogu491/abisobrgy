const sendTo = document.getElementById('sendTo');
const zoneContainer = document.getElementById('zoneContainer');

sendTo.addEventListener('change', function() {
  zoneContainer.style.display = (this.value === 'residents') ? 'block' : 'none';
});

function loadCounts() {
  fetch('get_counts.php')
    .then(res => res.json())
    .then(data => {
      document.getElementById('residentCount').textContent = data.residents;
      document.getElementById('officialCount').textContent = data.officials;
    })
    .catch(err => console.error("Error loading counts:", err));
}
loadCounts();
setInterval(loadCounts, 5000);

const announcementList = document.getElementById('announcementList');
const form = document.getElementById('announcementForm');
const viewAllLink = document.getElementById('viewAllLink');
let showAll = false;

function loadAnnouncements() {
  announcementList.innerHTML = "";
  fetch('get_announcements.php')
    .then(res => res.json())
    .then(announcements => {
      if (announcements.length === 0) {
        announcementList.innerHTML = "<li>No announcements yet.</li>";
        return;
      }

      let dataToShow = showAll ? announcements : announcements.slice(0,3);

      dataToShow.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `
          <strong>${item.title}</strong> – ${item.message}
          <br><small>Sent to: ${item.sendTo} | Zone: ${item.zone} | Posted on ${item.time}</small>
          <br>
          <button onclick="deleteAnnouncement(${item.id})"
            style="margin-top:5px; background:red; color:white; border:none; padding:5px 10px; cursor:pointer;">
            Delete
          </button>
        `;
        announcementList.appendChild(li);
      });

      const deleteAllBtn = document.createElement('button');
      deleteAllBtn.textContent = "Delete All Announcements";
      deleteAllBtn.style.cssText = "margin-top:10px; background:darkred; color:white; border:none; padding:8px 12px; cursor:pointer;";
      deleteAllBtn.onclick = deleteAllAnnouncements;
      announcementList.appendChild(deleteAllBtn);
    })
    .catch(err => console.error("Error loading announcements:", err));
}

form.addEventListener('submit', function(e) {
  e.preventDefault();

  const title = document.getElementById('title').value;
  const sendToVal = document.getElementById('sendTo').value;
  const message = document.getElementById('message').value;
  const zone = document.getElementById('zone').value;

  const params = new URLSearchParams();
  params.append("title", title);
  params.append("sendTo", sendToVal);
  params.append("message", message);
  params.append("zone", zone);

  fetch('send_announcement_unisms.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: params.toString()
  })
  .then(res => res.text())
  .then(response => {
    console.log("Server response:", response);
    if(response.trim() === "success") {
      alert("Announcement saved to database!");
      loadAnnouncements();
    } else {
      alert("Error: " + response);
    }
  })
  .catch(err => console.error("Error saving announcement:", err));

  form.reset();
});

viewAllLink.addEventListener('click', (e) => {
  e.preventDefault();
  showAll = true;
  loadAnnouncements();
  viewAllLink.style.display = 'none';
});

function deleteAnnouncement(id) {
  if (confirm("Are you sure you want to delete it?")) {
    fetch('delete_announcement.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + id
    })
    .then(res => res.text())
    .then(response => {
      if (response.trim() === "success") {
        alert("Announcement deleted!");
        loadAnnouncements();
      } else {
        alert("Error: " + response);
      }
    })
    .catch(err => console.error("Error deleting announcement:", err));
  }
}

function deleteAllAnnouncements() {
  if (confirm("Are you sure you want to delete ALL announcements?")) {
    fetch('delete_all_announcements.php', { method: 'POST' })
      .then(res => res.text())
      .then(response => {
        if (response.trim() === "success") {
          alert("All announcements deleted!");
          loadAnnouncements();
        } else {
          alert("Error: " + response);
        }
      })
      .catch(err => console.error("Error deleting all announcements:", err));
  }
}

loadAnnouncements();

const chartContainer = document.getElementById('chartContainer');

let populationChart;

function loadForecast() {
  fetch('get_forecast.php')
    .then(res => res.json())
    .then(data => {
      const years = data.map(r => r.year);
      const actual = data.map(r => parseInt(r.actual_population));
      const predicted = data.map(r => parseInt(r.predicted_population));
      const growth = data.map(r => parseFloat(r.growth_rate));

      const ctx = document.getElementById('populationChart').getContext('2d');

      if (populationChart) {
        populationChart.destroy();
      }

      populationChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: years,
          datasets: [
            { label:'Actual Population', data:actual, borderColor:'#2a1aff', fill:true },
            { label:'Predicted Population', data:predicted, borderColor:'#ff1a1a', borderDash:[5,5], fill:false },
            { label:'Growth Rate (%)', data:growth, borderColor:'#00cc00', fill:false, yAxisID:'y2' }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: { title:{display:true,text:'Population'} },
            y2: { position:'right', title:{display:true,text:'Growth Rate (%)'}, grid:{drawOnChartArea:false} }
          }
        }
      });
    })
    .catch(err => console.error("Error loading forecast:", err));
}
loadForecast();

document.getElementById('csvForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('upload_csv.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(response => {
    document.getElementById('uploadStatus').textContent = response;
    loadForecast();
  })
  .catch(err => console.error("Error uploading CSV:", err));
});


