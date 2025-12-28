import { useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Button } from './ui/button';
import { Switch } from './ui/switch';
import { Label } from './ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';
import { Clock, Plus, Trash2 } from 'lucide-react';

interface TimeSlot {
  start: string;
  end: string;
}

interface DayAvailability {
  enabled: boolean;
  slots: TimeSlot[];
}

interface AvailabilitySettings {
  timezone: string;
  days: {
    monday: DayAvailability;
    tuesday: DayAvailability;
    wednesday: DayAvailability;
    thursday: DayAvailability;
    friday: DayAvailability;
    saturday: DayAvailability;
    sunday: DayAvailability;
  };
}

const daysOfWeek = [
  { key: 'monday', label: 'Lundi' },
  { key: 'tuesday', label: 'Mardi' },
  { key: 'wednesday', label: 'Mercredi' },
  { key: 'thursday', label: 'Jeudi' },
  { key: 'friday', label: 'Vendredi' },
  { key: 'saturday', label: 'Samedi' },
  { key: 'sunday', label: 'Dimanche' },
];

const timezones = [
  'Europe/Paris',
  'Europe/London',
  'America/New_York',
  'America/Los_Angeles',
  'Asia/Tokyo',
  'Australia/Sydney',
];

export function AvailabilitySettings() {
  const [availability, setAvailability] = useState<AvailabilitySettings>({
    timezone: 'Europe/Paris',
    days: {
      monday: { enabled: true, slots: [{ start: '09:00', end: '17:00' }] },
      tuesday: { enabled: true, slots: [{ start: '09:00', end: '17:00' }] },
      wednesday: { enabled: true, slots: [{ start: '09:00', end: '17:00' }] },
      thursday: { enabled: true, slots: [{ start: '09:00', end: '17:00' }] },
      friday: { enabled: true, slots: [{ start: '09:00', end: '17:00' }] },
      saturday: { enabled: false, slots: [] },
      sunday: { enabled: false, slots: [] },
    },
  });

  const toggleDay = (day: string) => {
    setAvailability((prev) => ({
      ...prev,
      days: {
        ...prev.days,
        [day]: {
          ...prev.days[day as keyof typeof prev.days],
          enabled: !prev.days[day as keyof typeof prev.days].enabled,
        },
      },
    }));
  };

  const addTimeSlot = (day: string) => {
    setAvailability((prev) => ({
      ...prev,
      days: {
        ...prev.days,
        [day]: {
          ...prev.days[day as keyof typeof prev.days],
          slots: [
            ...prev.days[day as keyof typeof prev.days].slots,
            { start: '09:00', end: '17:00' },
          ],
        },
      },
    }));
  };

  const removeTimeSlot = (day: string, index: number) => {
    setAvailability((prev) => ({
      ...prev,
      days: {
        ...prev.days,
        [day]: {
          ...prev.days[day as keyof typeof prev.days],
          slots: prev.days[day as keyof typeof prev.days].slots.filter((_, i) => i !== index),
        },
      },
    }));
  };

  const updateTimeSlot = (day: string, index: number, field: 'start' | 'end', value: string) => {
    setAvailability((prev) => ({
      ...prev,
      days: {
        ...prev.days,
        [day]: {
          ...prev.days[day as keyof typeof prev.days],
          slots: prev.days[day as keyof typeof prev.days].slots.map((slot, i) =>
            i === index ? { ...slot, [field]: value } : slot
          ),
        },
      },
    }));
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center">
          <Clock className="h-5 w-5 mr-2" />
          Disponibilités et Horaires
        </CardTitle>
        <CardDescription>
          Configurez vos horaires de disponibilité pour chaque jour de la semaine
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-6">
        <div className="space-y-2">
          <Label>Fuseau horaire</Label>
          <Select
            value={availability.timezone}
            onValueChange={(value) => setAvailability({ ...availability, timezone: value })}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              {timezones.map((tz) => (
                <SelectItem key={tz} value={tz}>
                  {tz}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-4">
          {daysOfWeek.map(({ key, label }) => {
            const dayData = availability.days[key as keyof typeof availability.days];
            return (
              <div key={key} className="border rounded-lg p-4 space-y-3">
                <div className="flex items-center justify-between">
                  <Label htmlFor={key} className="text-base font-semibold">
                    {label}
                  </Label>
                  <Switch
                    id={key}
                    checked={dayData.enabled}
                    onCheckedChange={() => toggleDay(key)}
                  />
                </div>

                {dayData.enabled && (
                  <div className="space-y-2 pl-4">
                    {dayData.slots.map((slot, index) => (
                      <div key={index} className="flex items-center space-x-2">
                        <input
                          type="time"
                          value={slot.start}
                          onChange={(e) => updateTimeSlot(key, index, 'start', e.target.value)}
                          className="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm"
                        />
                        <span className="text-muted-foreground">à</span>
                        <input
                          type="time"
                          value={slot.end}
                          onChange={(e) => updateTimeSlot(key, index, 'end', e.target.value)}
                          className="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm"
                        />
                        {dayData.slots.length > 1 && (
                          <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            onClick={() => removeTimeSlot(key, index)}
                          >
                            <Trash2 className="h-4 w-4 text-red-500" />
                          </Button>
                        )}
                      </div>
                    ))}
                    <Button
                      type="button"
                      variant="outline"
                      size="sm"
                      onClick={() => addTimeSlot(key)}
                      className="mt-2"
                    >
                      <Plus className="h-3 w-3 mr-1" />
                      Ajouter un créneau
                    </Button>
                  </div>
                )}
              </div>
            );
          })}
        </div>

        <Button className="w-full">
          Enregistrer les disponibilités
        </Button>
      </CardContent>
    </Card>
  );
}
