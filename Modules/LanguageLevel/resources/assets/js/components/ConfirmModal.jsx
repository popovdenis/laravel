import React from "react";

export function ConfirmModal ({ message, onConfirm, onCancel }) {
    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div className="bg-white p-6 rounded shadow-md text-center">
                <p className="mb-4 text-gray-800 font-medium">{message}</p>
                <div className="flex justify-center space-x-2">
                    <button className="btn btn-secondary" onClick={onCancel}>Cancel</button>
                    <button className="btn btn-primary" onClick={onConfirm}>Confirm</button>
                </div>
            </div>
        </div>
    );
}
