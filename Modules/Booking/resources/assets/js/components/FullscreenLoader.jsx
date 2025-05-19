import React from 'react'

export default function FullscreenLoader() {
    return (
        <div className="fixed !mt-0 inset-0 bg-black bg-opacity-20 z-50 flex items-center justify-center">
            <div className="w-16 h-16 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </div>
    )
}
