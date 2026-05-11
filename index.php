<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>TG Earn - মিনি অ্যাপ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: linear-gradient(145deg, #0b2b26 0%, #0a1f1c 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, 'Roboto', sans-serif;
            padding: 16px;
            color: #e6f7f2;
            min-height: 100vh;
        }

        .container {
            max-width: 520px;
            margin: 0 auto;
        }

        /* কার্ড স্টাইল */
        .card {
            background: rgba(15, 35, 32, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 18px 20px;
            margin-bottom: 18px;
            border: 1px solid rgba(80, 210, 160, 0.25);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .balance-card {
            background: linear-gradient(135deg, #1f5e54, #0e3e37);
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
        }

        .balance-label {
            font-size: 14px;
            opacity: 0.8;
        }

        .balance-amount {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #f9e45b;
        }

        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            gap: 12px;
        }

        .stat-box {
            background: #0c2c27;
            border-radius: 24px;
            padding: 10px;
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 22px;
            font-weight: bold;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin: 15px 0;
            flex-wrap: wrap;
        }

        .btn {
            background: #2c7a64;
            border: none;
            padding: 14px 0;
            border-radius: 60px;
            font-weight: bold;
            font-size: 16px;
            color: white;
            flex: 1;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
            box-shadow: 0 3px 6px rgba(0,0,0,0.3);
        }

        .btn:active { transform: scale(0.97); background: #1f5e51; }
        .btn-warning { background: #b85c1a; }
        .btn-spin { background: #3f8f7a; }
        .btn-withdraw { background: #d4af37; color: #1e2a24; font-weight: bold; }

        .limits-text {
            font-size: 13px;
            background: #0d2a25;
            padding: 12px;
            border-radius: 24px;
            text-align: center;
            margin-top: 8px;
        }

        .notice-area {
            background: #2a2e1f;
            border-left: 5px solid #ffae42;
            border-radius: 24px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .notice-edit {
            background: #1e3b35;
            margin-top: 8px;
            border-radius: 28px;
            padding: 8px 12px;
            display: flex;
            gap: 8px;
        }

        .notice-edit input {
            flex: 1;
            background: #112f2a;
            border: none;
            padding: 10px 14px;
            border-radius: 40px;
            color: white;
            outline: none;
        }

        .notice-edit button {
            background: #348c74;
            border: none;
            padding: 0 18px;
            border-radius: 40px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .history {
            max-height: 200px;
            overflow-y: auto;
            font-size: 13px;
        }
        .history-item {
            border-bottom: 1px solid #296e5f;
            padding: 8px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-card {
            background: #1f423c;
            width: 85%;
            border-radius: 40px;
            padding: 24px;
            text-align: center;
        }
        .close-modal {
            margin-top: 12px;
            background: #bd7a3a;
            padding: 8px;
            border-radius: 40px;
            cursor: pointer;
        }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .btn { font-size: 14px; padding: 12px 0; }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- ব্যালেন্স কার্ড -->
    <div class="card balance-card">
        <div><span class="balance-label">💰 মোট ব্যালেন্স</span></div>
        <div><span class="balance-amount" id="userBalance">0.00</span> $</div>
    </div>

    <!-- স্ট্যাটাস লিমিট -->
    <div class="card">
        <div class="stats-row">
            <div class="stat-box">📺 আজকের Ad দেখেছেন<br><span id="todayAds" class="stat-value">0</span> / 20</div>
            <div class="stat-box">🎡 স্পিন লিমিট<br><span id="spinLeft" class="stat-value">20</span> / 20</div>
        </div>
        <div class="button-group">
            <button class="btn" id="watchAdBtn">👁️ দেখুন বিজ্ঞাপন (+$0.0010)</button>
            <button class="btn btn-spin" id="spinBtn">🎰 স্পিন করুন (+$0.0020)</button>
        </div>
        <div class="limits-text">
            📢 প্রতি বিজ্ঞাপন: $0.0010 (লিমিট ২০টি/দিন)<br>
            🎡 প্রতি স্পিন: $0.0020 (লিমিট ২০টি/দিন)<br>
            🎁 ডেইলি বোনাস: প্রতিদিন সকালে রিসেট + ফ্রি $0.005
        </div>
        <button class="btn btn-withdraw" id="withdrawBtn" style="margin-top: 12px;">💸 উইথড্র করুন</button>
    </div>

    <!-- নোটিশ সেকশন (এডিটেবল) -->
    <div class="notice-area">
        <div style="font-weight: bold; margin-bottom: 6px;">📢 নোটিশ বোর্ড</div>
        <div id="noticeTextDisplay">পিয় ইউজার বিকাশে কেউ টাকা তুলবেন না ধন্যবাদ   </div>
        <div class="notice-edit">
            <input type="text" id="noticeInput" placeholder="নতুন নোটিশ লিখুন...">
            <button id="updateNoticeBtn">আপডেট</button>
        </div>
        <div style="font-size: 11px; margin-top: 6px;">🔧 প্রশাসক: এডিট করে নোটিশ পরিবর্তন করুন</div>
    </div>

    <!-- উইথড্র পদ্ধতি ও ইতিহাস -->
    <div class="card">
        <div style="font-weight: bold; margin-bottom: 8px;">💳 পেমেন্ট মেথড</div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
            <span style="background:#10312b; padding:5px 12px; border-radius: 20px;">Bkash</span>
            <span style="background:#10312b; padding:5px 12px; border-radius: 20px;">Nagad</span>
            <span style="background:#10312b; padding:5px 12px; border-radius: 20px;">Rocket</span>
            <span style="background:#10312b; padding:5px 12px; border-radius: 20px;">Binance</span>
        </div>
        <div style="font-size: 13px; background: #0e2c27; border-radius: 18px; padding: 10px;">
            💰 মিনিমাম উইথড্র: $0.20 (≈20 ৳)<br>
            📝 লেনদেনের ইতিহাস: 
        </div>
        <div id="historyList" class="history" style="margin-top: 12px;">
            <div class="history-item">📌 স্বাগতম! বিজ্ঞাপন ও স্পিন করে আয় করুন</div>
        </div>
    </div>
</div>

<!-- উইথড্র মডাল -->
<div id="withdrawModal" class="modal">
    <div class="modal-card">
        <h3 style="margin-bottom: 12px;">উইথড্র ফরম</h3>
        <select id="methodSelect" style="background: #345e54; color:white; padding: 10px; border-radius: 32px; width: 100%; margin-bottom: 12px;">
            <option value="Bkash">Bkash</option>
            <option value="Nagad">Nagad</option>
            <option value="Rocket">Rocket</option>
            <option value="Binance">Binance (USDT)</option>
        </select>
        <input type="text" id="accountId" placeholder="একাউন্ট নম্বর / আইডি" style="width:100%; padding:12px; border-radius: 40px; background: #163932; border: none; color: white; margin-bottom: 12px;">
        <div>উইথড্র পরিমাণ: <strong id="withdrawAmountShow">0.20</strong> $</div>
        <div style="font-size:12px; margin:10px 0;">ন্যূনতম $0.20 | ব্যালেন্স: <span id="modalBalance">0.00</span>$</div>
        <button id="confirmWithdrawBtn" class="btn" style="background: #7abf3c;">কনফার্ম করুন</button>
        <div class="close-modal" id="closeModalBtn">বাতিল করুন</div>
    </div>
</div>

<script>
    // ---------- স্টোরেজ কী ----------
    const STORAGE = {
        balance: 'tg_earn_balance',
        adsSeen: 'tg_earn_ads_today',
        spinsUsed: 'tg_earn_spins_today',
        lastDate: 'tg_earn_last_date',
        withdrawHistory: 'tg_withdraw_history',
        customNotice: 'tg_custom_notice'
    };

    // ডিফল্ট নোটিশ
    const DEFAULT_NOTICE = "পিয় ইউজার বিকাশে কেউ টাকা তুলবেন না ধন্যবাদ   ";

    // গ্লোবাল ভেরিয়েবল
    let userBalance = 0.00;
    let adsWatchedToday = 0;
    let spinsDoneToday = 0;
    let currentDateKey = new Date().toLocaleDateString('en-CA'); // YYYY-MM-DD

    // DOM এলিমেন্ট
    const balanceEl = document.getElementById('userBalance');
    const todayAdsSpan = document.getElementById('todayAds');
    const spinLeftSpan = document.getElementById('spinLeft');
    const watchAdBtn = document.getElementById('watchAdBtn');
    const spinBtn = document.getElementById('spinBtn');
    const withdrawBtn = document.getElementById('withdrawBtn');
    const withdrawModal = document.getElementById('withdrawModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const confirmWithdrawBtn = document.getElementById('confirmWithdrawBtn');
    const methodSelect = document.getElementById('methodSelect');
    const accountIdInput = document.getElementById('accountId');
    const withdrawAmountShow = document.getElementById('withdrawAmountShow');
    const modalBalanceSpan = document.getElementById('modalBalance');
    const historyListDiv = document.getElementById('historyList');
    const noticeTextDisplay = document.getElementById('noticeTextDisplay');
    const noticeInput = document.getElementById('noticeInput');
    const updateNoticeBtn = document.getElementById('updateNoticeBtn');

    // helper: সেভ ব্যালেন্স
    function saveBalance() {
        localStorage.setItem(STORAGE.balance, userBalance.toFixed(6));
    }

    function loadBalance() {
        let bal = localStorage.getItem(STORAGE.balance);
        if(bal && !isNaN(parseFloat(bal))) userBalance = parseFloat(bal);
        else userBalance = 0.00;
        updateBalanceUI();
    }

    function updateBalanceUI() {
        balanceEl.innerText = userBalance.toFixed(4);
        modalBalanceSpan.innerText = userBalance.toFixed(4);
    }

    // ডেইলি রিসেট চেক + বোনাস
    function checkDailyReset() {
        let savedDate = localStorage.getItem(STORAGE.lastDate);
        let today = new Date().toLocaleDateString('en-CA');
        if(savedDate !== today) {
            // আগের দিনের ডাটা রিসেট
            adsWatchedToday = 0;
            spinsDoneToday = 0;
            localStorage.setItem(STORAGE.adsSeen, adsWatchedToday);
            localStorage.setItem(STORAGE.spinsUsed, spinsDoneToday);
            localStorage.setItem(STORAGE.lastDate, today);
            
            // ডেইলি বোনাস $0.005
            let bonus = 0.0050;
            userBalance += bonus;
            saveBalance();
            updateBalanceUI();
            addHistory(`🎁 ডেইলি বোনাস +$${bonus.toFixed(4)} স্বাগতম! (প্রতিদিন বোনাস)`);
        } else {
            // লোড আজকের লিমিট
            let storedAds = localStorage.getItem(STORAGE.adsSeen);
            let storedSpins = localStorage.getItem(STORAGE.spinsUsed);
            adsWatchedToday = (storedAds !== null) ? parseInt(storedAds) : 0;
            spinsDoneToday = (storedSpins !== null) ? parseInt(storedSpins) : 0;
        }
        updateLimitUI();
    }

    function updateLimitUI() {
        todayAdsSpan.innerText = `${adsWatchedToday} / 20`;
        let spinsRemain = 20 - spinsDoneToday;
        spinsRemain = spinsRemain < 0 ? 0 : spinsRemain;
        spinLeftSpan.innerText = `${spinsRemain} / 20`;
        // বাটন ডিজেবল ইফেক্ট optional
    }

    // ইতিহাস যোগ
    function addHistory(message) {
        let historyArr = [];
        let existing = localStorage.getItem(STORAGE.withdrawHistory);
        if(existing) {
            try {
                historyArr = JSON.parse(existing);
            } catch(e) {}
        }
        historyArr.unshift({ msg: message, time: new Date().toLocaleString() });
        if(historyArr.length > 20) historyArr.pop();
        localStorage.setItem(STORAGE.withdrawHistory, JSON.stringify(historyArr));
        renderHistory();
    }

    function renderHistory() {
        let historyArr = [];
        let existing = localStorage.getItem(STORAGE.withdrawHistory);
        if(existing) {
            try {
                historyArr = JSON.parse(existing);
            } catch(e) {}
        }
        if(historyArr.length === 0) {
            historyListDiv.innerHTML = `<div class="history-item">📌 কোনো লেনদেন নেই</div>`;
            return;
        }
        let html = '';
        for(let h of historyArr) {
            html += `<div class="history-item">🕒 ${h.time} - ${h.msg}</div>`;
        }
        historyListDiv.innerHTML = html;
    }

    // স্পিন ইনকাম (০.০০২০ ডলার)
    function doSpin() {
        if(spinsDoneToday >= 20) {
            alert("আজকের স্পিন লিমিট (২০ বার) শেষ! কাল আবার চেষ্টা করুন।");
            return false;
        }
        let earn = 0.0020;
        userBalance += earn;
        spinsDoneToday++;
        localStorage.setItem(STORAGE.spinsUsed, spinsDoneToday);
        saveBalance();
        updateBalanceUI();
        updateLimitUI();
        addHistory(`🎡 স্পিন করে +$${earn.toFixed(4)} অর্জন করেছেন`);
        return true;
    }

    // এডস দেখা
    function watchAd() {
        if(adsWatchedToday >= 20) {
            alert("আজকের বিজ্ঞাপন দেখার লিমিট ২০ টি সম্পূর্ণ! কাল আবার আসুন।");
            return false;
        }
        let earn = 0.0010;
        userBalance += earn;
        adsWatchedToday++;
        localStorage.setItem(STORAGE.adsSeen, adsWatchedToday);
        saveBalance();
        updateBalanceUI();
        updateLimitUI();
        addHistory(`📺 বিজ্ঞাপন দেখে +$${earn.toFixed(4)} ইনকাম`);
        return true;
    }

    // উইথড্র চেক (ন্যূনতম 0.20)
    function attemptWithdraw(method, account, amount) {
        if(amount < 0.20) {
            alert(`উইথড্র করার জন্য ন্যূনতম পরিমাণ $0.20 USD প্রয়োজন। আপনার ব্যালেন্স: $${userBalance.toFixed(4)}`);
            return false;
        }
        if(userBalance < amount) {
            alert("পর্যাপ্ত ব্যালেন্স নেই!");
            return false;
        }
        // সিমুলেটেড উইথড্র
        userBalance -= amount;
        saveBalance();
        updateBalanceUI();
        let msg = `উইথড্র $${amount.toFixed(4)} via ${method} (${account}) - প্রসেসিং`;
        addHistory(`✅ ${msg}`);
        alert(`সফলভাবে উইথড্র রিকোয়েস্ট জমা! ${method} একাউন্টে ${amount}$ স্থানান্তর হবে।`);
        return true;
    }

    // নোটিশ আপডেট
    function loadNotice() {
        let savedNotice = localStorage.getItem(STORAGE.customNotice);
        if(savedNotice !== null && savedNotice.trim() !== "") {
            noticeTextDisplay.innerText = savedNotice;
        } else {
            noticeTextDisplay.innerText = DEFAULT_NOTICE;
        }
        noticeInput.value = "";
    }

    function setNotice(newNotice) {
        if(newNotice.trim() === "") return;
        localStorage.setItem(STORAGE.customNotice, newNotice);
        noticeTextDisplay.innerText = newNotice;
        addHistory(`📢 নতুন নোটিশ আপডেট: "${newNotice.substring(0,40)}"`);
    }

    // মডাল কন্ট্রোল
    function openWithdrawModal() {
        if(userBalance < 0.20) {
            alert(`মিনিমাম উইথড্র $0.20 (প্রায় ২০ টাকা)। আপনার ব্যালেন্স $${userBalance.toFixed(4)}। আরও আয় করুন।`);
            return;
        }
        withdrawAmountShow.innerText = Math.min(0.20, userBalance).toFixed(4);
        modalBalanceSpan.innerText = userBalance.toFixed(4);
        withdrawModal.style.display = 'flex';
    }

    function closeModal() {
        withdrawModal.style.display = 'none';
        accountIdInput.value = '';
    }

    // ইভেন্ট লিসেনার
    watchAdBtn.addEventListener('click', () => {
        watchAd();
    });
    spinBtn.addEventListener('click', () => {
        doSpin();
    });
    withdrawBtn.addEventListener('click', openWithdrawModal);
    closeModalBtn.addEventListener('click', closeModal);
    confirmWithdrawBtn.addEventListener('click', () => {
        let method = methodSelect.value;
        let account = accountIdInput.value.trim();
        if(account === "") {
            alert("দয়া করে আপনার একাউন্ট নম্বর/আইডি দিন!");
            return;
        }
        let requestedAmount = 0.20;   // exact minimum amount fixed wise
        if(userBalance < 0.20) {
            alert("ব্যালেন্স কম! ন্যূনতম $0.20 প্রয়োজন");
            closeModal();
            return;
        }
        attemptWithdraw(method, account, 0.20);
        closeModal();
    });

    // মডালের বাইরে ক্লিক করলে বন্ধ
    window.onclick = function(e) {
        if(e.target === withdrawModal) closeModal();
    };

    // লিমিট সেভ টু স্টোরেজ ইফ ডেইলি লোড হয়েছে 
    function initialSync() {
        loadBalance();
        checkDailyReset();   // এটার মধ্যে adsWatchedToday, spinsDoneToday রিসেট/লোড হবে
        updateLimitUI();
        renderHistory();
        loadNotice();
    }

    // ব্যাকগ্রাউন্ডে ডেইলি রিসেট নিশ্চিত করতে প্রতিবার ট্যাব খোলায় রান
    window.addEventListener('load', () => {
        initialSync();
    });

    // নোটিশ বাটন লিসেনার
    updateNoticeBtn.addEventListener('click', () => {
        let newNoticeVal = noticeInput.value;
        if(newNoticeVal.trim() !== "") {
            setNotice(newNoticeVal);
            noticeInput.value = "";
        } else {
            alert("নোটিশ লিখুন পরিবর্তনের জন্য");
        }
    });

    // ডেইলি বোনাস যাতে ব্যবহারকারী যদি পুরো দিন না খোলে তাও কাউন্টার ঠিক থাকে
    // অতিরিক্ত: বিজ্ঞাপন ও স্পিন পরবর্তী রিসেট ঠিকমত।
</script>
</body>
</html>
