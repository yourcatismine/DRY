import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
import { getDatabase, ref, onChildAdded, onValue } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

async function loadFirebaseConfig() {
  try {
    const response = await fetch('./ADfirebase/ADfirebaseconfig.php'); //Get ENV config 
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const config = await response.json();

    
    if (!config.apiKey || !config.projectId) {
      throw new Error('Invalid Firebase configuration received');
    }
    
    return config;
  } catch (error) {
    console.error('Failed to load Firebase config from environment:', error);
    throw error; //
  }
}

async function initializeFirebaseApp() {
  try {
    const firebaseConfig = await loadFirebaseConfig();
    const app = initializeApp(firebaseConfig);
    return getDatabase(app);
  } catch (error) {
    console.error('Failed to initialize Firebase:', error);
    throw error;
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  try {
    const database = await initializeFirebaseApp();
    
    const tbody = document.getElementById("visitor-table-body");
    const visitorCountElement = document.getElementById("visitor-count");
    const visitorsRef = ref(database, "visitors");

    onChildAdded(visitorsRef, snapshot => {
      const { ip, country, platform, city, region, timestamp, referrer } = snapshot.val();

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${ip}</td>
        <td>${platform}</td>
        <td>${country}</td>
        <td>${city}</td>
        <td>${region || 'N/A'}</td>
        <td>${new Date(timestamp).toLocaleString()}</td>
        <td>${referrer}</td>
      `;
      tbody.appendChild(tr);
    });

    onValue(visitorsRef, snapshot => {
      const data = snapshot.val() || {};
      const total = Object.keys(data).length;
      if (visitorCountElement) {
        animateVisitorCount(0, total, 300);
      }
    });

    function animateVisitorCount(start, end, duration) {
      const range = end - start;
      if (range <= 0) {
        visitorCountElement.textContent = end;
        return;
      }
      let startTime = null;
      function step(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const current = Math.min(start + Math.floor((progress / duration) * range), end);
        visitorCountElement.textContent = current;
        if (progress < duration) {
          window.requestAnimationFrame(step);
        } else {
          visitorCountElement.textContent = end;
        }
      }
      window.requestAnimationFrame(step);
    }

    const counters = [
      { id: "projects-counter", target: 9, duration: 300 },
      { id: "languages-counter", target: 5, duration: 300 },
      { id: "github-counter", target: 4, duration: 300 }
    ];

    counters.forEach(({ id, target, duration }) => {
      const counterElement = document.getElementById(id);
      if (counterElement) {
        animateNumber(counterElement, target, duration);
      }
    });

    function animateNumber(element, target, duration) {
      const start = 0;
      const range = target - start;
      let startTime = null;

      function step(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const current = Math.min(start + Math.floor((progress / duration) * range), target);
        element.textContent = current;
        if (progress < duration) {
          window.requestAnimationFrame(step);
        } else {
          element.textContent = target;
        }
      }

      window.requestAnimationFrame(step);
    }

  } catch (error) {
    console.error('Application initialization failed:', error);
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = 'color: red; padding: 10px; background: #ffebee; border: 1px solid #f44336; margin: 10px;';
    errorDiv.textContent = 'Failed to load application configuration. Please check the console for details.';
    document.body.insertBefore(errorDiv, document.body.firstChild);
  }
});