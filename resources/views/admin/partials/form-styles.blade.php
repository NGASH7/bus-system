<style>
    .btn-back {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        color: #800000;
        border-color: #800000;
        background: #fffafa;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0 12px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #800000;
        box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.12);
    }

    .req {
        color: #b91c1c;
    }

    .error {
        font-size: 12px;
        color: #dc2626;
        font-weight: 500;
    }

    .form-actions {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        color: #111827;
        border-color: #9ca3af;
        background: #f9fafb;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 40px;
        padding: 0 16px;
        border-radius: 10px;
        border: none;
        background: #800000;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(128, 0, 0, 0.25);
    }

    .btn-save:hover {
        background: #600000;
        transform: translateY(-1px);
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
