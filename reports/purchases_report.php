<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Purchases Report</title>
  <script src="../js/vendor/html2pdf.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      visibility: hidden;
    }

    .stats-container {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .stat-card {
      flex: 0 1 200px;
      background: white;
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .stat-icon {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .stat-title {
      margin: 0 0 0.5rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: #718096;
    }

    .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
    }

    .badge {
      display: inline-block;
      padding: 0.35em 0.65em;
      font-size: 0.75em;
      font-weight: 700;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 0.25rem;
    }

    .bg-green-soft {
      background-color: rgba(16, 185, 129, 0.1);
      color: #10b981;
    }

    .bg-yellow-soft {
      background-color: rgba(245, 158, 11, 0.1);
      color: #f59e0b;
    }

    .bg-red-soft {
      background-color: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    .bg-blue-soft {
      background-color: rgba(59, 130, 246, 0.1);
      color: #3b82f6;
    }
  </style>
</head>

<body>
  <script>
    (async () => {
      const container = document.createElement('div');
      container.style.padding = '2.5rem';
      container.style.fontFamily = "'Inter', sans-serif";
      container.style.maxWidth = '1000px';
      container.style.margin = '0 auto';
      container.style.color = '#2d3748';

      const header = document.createElement('div');
      header.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
      header.style.color = 'white';
      header.style.padding = '1.5rem 2rem';
      header.style.borderRadius = '8px';
      header.style.marginBottom = '2rem';
      header.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';

      const title = document.createElement('h1');
      title.textContent = 'Purchases Report';
      title.style.margin = '0';
      title.style.fontSize = '1.8rem';
      title.style.fontWeight = '600';
      header.appendChild(title);

      const date = document.createElement('p');
      date.style.margin = '0.5rem 0 0';
      date.style.opacity = '0.9';
      date.style.fontSize = '0.9rem';
      date.style.fontWeight = '300';
      date.innerHTML = `<i class="far fa-calendar-alt"></i> Generated on: ${new Date().toLocaleString()}`;
      header.appendChild(date);
      container.appendChild(header);

      try {
        const res = await fetch('../api/custom/purchases-report.php');
        if (!res.ok) throw new Error('Network response was not OK');

        const {
          summary,
          recent_purchases
        } = await res.json();
        if (!summary || !recent_purchases) throw new Error('Missing data from API');

        // 🟢 Continue rendering only if data is valid
        const statsContainer = document.createElement('div');
        statsContainer.className = 'stats-container';

        const statCards = [{
            title: 'Total Purchases',
            value: summary.total_purchases,
            icon: 'fas fa-shopping-basket',
            color: '#4f46e5'
          },
          {
            title: 'Completed',
            value: summary.purchases_completed,
            icon: 'fas fa-check-circle',
            color: '#10b981'
          },
          {
            title: 'Pending',
            value: summary.purchases_pending,
            icon: 'fas fa-clock',
            color: '#f59e0b'
          },
          {
            title: 'Canceled',
            value: summary.purchases_canceled,
            icon: 'fas fa-times-circle',
            color: '#ef4444'
          },
          {
            title: 'Total Spent',
            value: summary.total_spent ? summary.total_spent.toLocaleString() + ' DA' : '0 DA',
            icon: 'fas fa-money-bill-wave',
            color: '#3b82f6'
          }
        ];

        statCards.forEach(card => {
          const cardEl = document.createElement('div');
          cardEl.className = 'stat-card';
          cardEl.innerHTML = `
        <div class="stat-icon" style="color: ${card.color}"><i class="${card.icon}"></i></div>
        <h3 class="stat-title">${card.title}</h3>
        <div class="stat-value" style="color: ${card.color}">${card.value}</div>
      `;
          statsContainer.appendChild(cardEl);
        });

        container.appendChild(statsContainer);

        const sectionHeader = document.createElement('h2');
        sectionHeader.innerHTML = `<i class="fas fa-list-alt" style="color: #6366f1;"></i> Recent Purchases`;
        sectionHeader.style.fontSize = '1.3rem';
        sectionHeader.style.margin = '2rem 0 1rem';
        sectionHeader.style.color = '#2d3748';
        container.appendChild(sectionHeader);

        const table = document.createElement('table');
        table.style.width = '100%';
        table.style.borderCollapse = 'separate';
        table.style.borderSpacing = '0';
        table.style.borderRadius = '8px';
        table.style.overflow = 'hidden';
        table.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';

        const thead = document.createElement('thead');
        thead.style.background = '#f7fafc';
        thead.style.color = '#4a5568';
        thead.innerHTML = `
      <tr>
        <th style='padding: 1rem;'>Purchase ID</th>
        <th style='padding: 1rem;'>Supplier</th>
        <th style='padding: 1rem;'>Date</th>
        <th style='padding: 1rem; text-align:right;'>Amount</th>
        <th style='padding: 1rem; text-align:center;'>Products</th>
        <th style='padding: 1rem; text-align:center;'>Status</th>
      </tr>
    `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        recent_purchases.forEach((purchase, index) => {
          const row = document.createElement('tr');
          row.style.background = index % 2 === 0 ? 'white' : '#f8fafc';

          const deliveryStatus =
            purchase.Delivery_Status === 'Recieved' ?
            '<span class="badge bg-green-soft">Received</span>' :
            purchase.Delivery_Status === 'Pending' ?
            '<span class="badge bg-yellow-soft">Pending</span>' :
            purchase.Delivery_Status === 'Canceled' ?
            '<span class="badge bg-red-soft">Canceled</span>' :
            `<span class="badge bg-yellow-soft">${purchase.Delivery_Status}</span>`;

          row.innerHTML = `
        <td style='padding: 1rem;'>#${purchase.Purchase_ID}</td>
        <td style='padding: 1rem;'>${purchase.Supplier_Name}</td>
        <td style='padding: 1rem;'>${new Date(purchase.Purchase_Date).toLocaleDateString()}</td>
        <td style='padding: 1rem; text-align:right;'>${purchase.Total_Amount.toLocaleString()} DA</td>
        <td style='padding: 1rem; text-align:center;'>${purchase.products_count}</td>
        <td style='padding: 1rem; text-align:center;'>${deliveryStatus}</td>
      `;
          tbody.appendChild(row);
        });

        table.appendChild(tbody);
        container.appendChild(table);

        const footer = document.createElement('div');
        footer.textContent = `© ${new Date().getFullYear()} Inventory Management System`;
        footer.style.marginTop = '2rem';
        footer.style.textAlign = 'center';
        footer.style.color = '#718096';
        footer.style.fontSize = '0.8rem';
        footer.style.borderTop = '1px solid #e2e8f0';
        footer.style.paddingTop = '1rem';
        container.appendChild(footer);

        document.body.appendChild(container);
        document.body.style.visibility = 'visible';

        html2pdf().set({
          margin: 10,
          filename: `Purchases_Report_${new Date().toISOString().slice(0, 10)}.pdf`,
          image: {
            type: 'jpeg',
            quality: 0.98
          },
          html2canvas: {
            scale: 2,
            useCORS: true
          },
          jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
          }
        }).from(container).save();

      } catch (error) {
        console.error("Error loading purchases data:", error);
        const errorEl = document.createElement('div');
        errorEl.textContent = '❌ Failed to load purchases report. Please try again later.';
        errorEl.style.color = 'red';
        errorEl.style.fontSize = '1.2rem';
        errorEl.style.padding = '2rem';
        document.body.appendChild(errorEl);
        document.body.style.visibility = 'visible';
      }
    })();
  </script>
</body>

</html>