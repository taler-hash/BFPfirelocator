import L from 'leaflet'
import _FireIcon from '@/Assets/FireIcon.png'
import _StationIcon from '@/Assets/StationIcon.png'
import _FireFighterIcon from '@/Assets/FireFighter.png'
import _HydrantIcon from '@/Assets/HydrantIcon.png'

export const FireIcon = L.icon({
    iconUrl: _FireIcon,
    iconSize: [40, 50],
    iconAnchor: [20, 40]
})

export const StationIcon = L.icon({
    iconUrl: _StationIcon,
    iconSize: [20, 30],
    iconAnchor: [20, 40]
})

export const FireFightericon = L.icon({
    iconUrl: _FireFighterIcon,
    iconSize: [30, 30],
    iconAnchor: [20, 40]
})

export const HydrantIcon = L.icon({
    iconUrl: _HydrantIcon,
    iconSize: [30, 30],
    iconAnchor: [20, 40]
})