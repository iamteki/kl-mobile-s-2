<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-group">
        <span class="filter-label">Experience Level:</span>
        <div class="filter-options">
            <button class="filter-btn active">All</button>
            <button class="filter-btn">Entry Level</button>
            <button class="filter-btn">Professional</button>
            <button class="filter-btn">Premium</button>
        </div>
    </div>
    <div class="filter-group mt-3">
        <span class="filter-label">Event Type:</span>
        <div class="filter-options">
            <button class="filter-btn active">All Events</button>
            <button class="filter-btn">Corporate</button>
            <button class="filter-btn">Wedding</button>
            <button class="filter-btn">Private Party</button>
            <button class="filter-btn">Concert</button>
        </div>
    </div>
</div>

<style>
/* Filter Section */
.filter-section {
    background-color: var(--bg-card);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid var(--border-dark);
}

.filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}

.filter-label {
    color: var(--text-light);
    font-weight: 600;
    min-width: 100px;
}

.filter-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    background-color: var(--bg-dark);
    color: var(--text-gray);
    border: 1px solid var(--border-dark);
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    transition: all 0.3s;
    cursor: pointer;
}

.filter-btn:hover {
    background-color: var(--bg-card-hover);
    color: var(--text-light);
    border-color: var(--primary-purple);
}

.filter-btn.active {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    border-color: transparent;
}
</style>